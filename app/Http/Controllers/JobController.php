<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use App\Notifications\RespondMessageNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display all jobs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);

        $jobs = Job::query()
            // Check if customer status is active
            ->with('customer')
            ->orderByDesc('id')
            ->whereHas('customer', function (Builder $query) {
                $query->where('active', '=', true);
            });
        // TODO: ->where('status', '=', 'approved');

        if ($title = $request->get('title'))
            $jobs->whereRaw('`title` like ?', ['%' . $title . '%']);

        if ($type = $request->get('work_type'))
            $jobs->where('work_type', '=', $type);

        if ($location_id = $request->get('location_id'))
            $jobs->isLocation($location_id);

        if ($category_id = $request->get('category_id'))
            $jobs->where('category_id', '=', $category_id);

        /** @see https://laravel.com/docs/9.x/queries#json-where-clauses */
        if ($salary = $request->get('salary'))
            $jobs->where('salary->amount', 'like', '%' . $salary . '%');

        if ($range_start = $request->get('start'))
            $jobs->where('salary->amount', '>=', $range_start);

        if ($range_end = $request->get('end'))
            $jobs->where('salary->amount', '<=', $range_end);

        if ($currency = $request->get('currency'))
            $jobs->whereJsonContains('salary->currency', $currency);

        if ($sphere = $request->get('sphere'))
            $jobs->whereJsonContains('sphere', $sphere);

        return response()->json([
            'status' => true,
            'jobs' => $jobs->paginate($params['limit'] ?? null)
        ]);
    }

    public function customer_releted_jobs(Request $request, $id)
    {
        // return response()->json($id);
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);
        $jobs = Job::query()
            // Check if customer status is active
            ->with('customer')
            ->orderByDesc('id')
            ->WhereHas('customer', function (Builder $query) use ($id) {
                $query->where('active', '=', true);
                $query->where('id', $id);
            })
            ->take($params['limit'] ?? 7)->get();
        return response()->json([
            'status' => true,
            'jobs' => $jobs
        ]);
    }

    public function similar_jobs(Request $request)
    {

        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);

        $jobs = Job::query()
            // Check if customer status is active
            // ->with('customer')
            ->when(request('title'), function ($query) {
                $query->whereRaw('`title` like ?', ['%' . request('title') . '%']);
            })
            ->when(request('work_type'), function ($query) {
                $query->where('work_type', '=', request('work_type'));
            })
            ->when(request('location_id'), function ($query) {
                $query->isLocation(request('location_id'));
            })
            ->when(request('category_id'), function ($query) {
                $query->where('category_id', '=', request('category_id'));
            })
            ->when(request('salary'), function ($query) {
                $query->where('salary->amount', 'like', '%' . request('salary') . '%');
            })
            ->when(request('start'), function ($query) {
                $query->where('salary->amount', '>=', request('start'));
            })
            ->when(request('end'), function ($query) {
                $query->where('salary->amount', '<=', request('end'));
            })
            ->when(request('currency'), function ($query) {
                $query->whereJsonContains('salary->currency', request('currency'));
            })
            ->when(request('sphere'), function ($query) {
                $query->whereJsonContains('sphere', request('sphere'));
            })
            ->orderByDesc('id')
            // ->WhereHas('customer', function (Builder $query) {
            //     $query->where('active', '=', true);
            // })
            ->take($params['limit'] ?? 7)->get();
        return response()->json([
            'status' => true,
            'jobs' => $jobs
        ]);
    }

    /**
     * Get guide information
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function get(string $slug): JsonResponse
    {
        $job = Job::query()->with('customer')
            ->whereHas('customer', function (Builder $query) {
                $query->where('active', '=', true);
            })
            // TODO: ->where('status', '=', 'approved')
            ->findOrFail($slug);

        _auth()->check() && _user()->jobStats()
            ->syncWithoutDetaching($job);

        return response()->json([
            'status' => true,
            'job' => $job
        ]);
    }

    public function respond(Request $request): JsonResponse
    {
        $params = $request->validate([
            'resume_id' => ['required', 'numeric'],
            'job_slug' => ['required', 'string'],
            'message' => ['nullable', 'string']
        ]);

        /** @var Authenticatable|User $user */
        $user = _auth()->user();

        $resume = Resume::query()->findOrFail($request->input('resume_id'));
        $job = Job::query()->findOrFail($request->input('job_slug'));

        $chat = $job->chats()->create([
            'job_slug' => $params['job_slug'],
            'resume_id' => $params['resume_id'],
            'customer_id' => $job->customer->id,
            'candidate_id' => $user->candidate->id,
            'status' => 'review'
        ]);

        @$params['message'] && $job->chats()->find($chat->id)->messages()->create([
            'message' => $params['message'],
            'role' => $user->role
        ]);

        $job->customer->user->notify(new RespondMessageNotification([
            'candidate' => $user->toArray(),
            'resume' => $resume->toArray(),
            'job' => $job->toArray()
        ]));

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Create vacancy
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'position' => ['string', 'required'],
            'location' => ['numeric', 'required'],
            'experience' => ['string', 'required'],
            'sphere' => ['array', 'required'],
            'category_id' => ['numeric', 'required'],
            'languages' => ['nullable'],
            'education_level' => ['string', 'nullable'],
            'salary' => ['array:amount,currency,agreement', 'required'],
            'work_type' => ['string', 'required', 'in:fulltime,remote,partial,hybrid'],
            'about' => ['string', 'required'],
            'work_hours' => ['string', 'nullable'],
            'for_communication_phone' => ['array', 'nullable'],
            'for_communication_link' => ['array', 'nullable'],
            'recruitment' => ['boolean', 'nullable'],
            'strengthening' => ['boolean', 'nullable']
        ]);

        $job = $request->user()->customer->jobs()->create([
            'title' => $params['position'],
            'salary' => $params['salary'],
            'about' => $params['about'],
            'work_type' => $params['work_type'],
            'experience' => $params['experience'],
            'location_id' => $params['location'],
            'languages' => $params['languages'],
            'education_level' => $params['education_level'] ?? null, // TODO: remove `null` value
            'sphere' => $params['sphere'],
            'category_id' => $params['category_id'],
            'slug' => null,
            'status' => 'approved',
            'work_hours'=> $params['work_hours'] ?? null,
            'for_communication_phone'=> $params['for_communication_phone'] ?? null,
            'for_communication_link'=> $params['for_communication_link'] ?? null
        ]);

        if (@$params['recruitment'] || @$params['strengthening']) {
            $message = "🆕 <b>" . $job->title . "</b>\n";
            $message .= "🏢 Kompaniya: <b>" . $job->customer->name . "</b>\n";
            $message .= "📞 Telefon raqam: " . $job->customer->user->phone . "\n\n";
            $message .= "📄 Xizmatlar:\n";
            $message .= $params['recruitment'] ? "- Xodim tanlash (подбор персонала)\n" : '';
            $message .= $params['strengthening'] ? "- E'lonni kuchaytirish (реклама | усиление объявление о вакансии)\n" : '';

            Http::withoutVerifying()->post("https://api.telegram.org/bot5777417067:AAGvh21OUGVQ7nmSnLbIhzTiZxoyMQMIZKk/sendMessage", [
                'chat_id' => '-844005009',
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard' => [[
                        [
                            'text' => "↗️ Vakansiyani ko'rish",
                            'url' => 'https://jobo.uz/jobs/' . $job->slug
                        ]
                    ]]
                ])
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Job successfully created',
        ]);
    }

    /**
     * Update vacancy
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function edit(Request $request, string $slug): JsonResponse
    {
        $params = $request->validate([
            'position' => ['string', 'required'],
            'location' => ['numeric', 'required'],
            'experience' => ['string', 'required'],
            'sphere' => ['array', 'required'],
            'category_id' => ['numeric', 'required'],
            'languages' => ['nullable'],
            'education_level' => ['string', 'nullable'],
            'salary' => ['array:amount,currency,agreement', 'required'],
            'work_type' => ['string', 'required', 'in:fulltime,remote,partial,hybrid'],
            'about' => ['string', 'required'],
            'work_hours' => ['string', 'nullable'],
            'for_communication_phone' => ['array', 'nullable'],
            'for_communication_link' => ['array', 'nullable'],
        ]);

        $job = $request->user()->customer->jobs()->findOrFail($slug);
        $job->update([
            'title' => $params['position'],
            'salary' => $params['salary'],
            'about' => $params['about'],
            'work_type' => $params['work_type'],
            'experience' => $params['experience'],
            'location_id' => $params['location'],
            'languages' => $params['languages'],
            'education_level' => $params['education_level'] ?? null, // TODO: remove `null` value
            'category_id' => $params['category_id'],
            'sphere' => $params['sphere'],
            'status' => 'approved',
            'work_hours'=> $params['work_hours'] ?? null,
            'for_communication_phone'=> $params['for_communication_phone'] ?? null,
            'for_communication_link'=> $params['for_communication_link'] ?? null
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * Destroy vacancy
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function destroy(Request $request, string $slug): JsonResponse
    {
        $job = $request->user()->customer->jobs()->findOrFail($slug);
        $job->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully deleted'
        ]);
    }

    /**
     * Description
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function acceptance(Request $request): JsonResponse
    {
        $params = $request->validate([
            'job_slug' => ['string', 'required'],
            'candidate_id' => ['numeric', 'required'],
            'status' => ['string', 'in:approve,reject', 'required'],
            'message' => ['string', 'nullable']
        ]);

        /** @var Authenticatable|User $user */
        $user = _auth()->user();
        $chat = $user->customer->chats()->where(function (Builder $query) use ($params) {
            $query->where('candidate_id', '=', $params['candidate_id']);
            $query->where('job_slug', '=', $params['job_slug']);
        })->firstOrFail();

        $chat->update([
            'status' => $params['status']
        ]);

        @$params['message'] && $chat->messages()->create([
            'message' => $params['message']
        ]);

        $chat->candidate->user->notify(new RespondMessageNotification([
            'candidate' => $chat->candidate->toArray(),
            'customer' => $user->customer->toArray(),
            'job' => $chat->job->toArray(),
            'message' => $params['message'] ?? null
        ]));

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Get applications list
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function applications(string $slug): JsonResponse
    {
        $job = Job::query()->findOrFail($slug);

        $data = $job->chats()->with(['candidate', 'candidate.user'])->get()->makeHidden([
            'job_slug', 'resume_id',
            'customer_id', 'candidate_id',
            'created_at', 'updated_at',
            'customer', 'job'
        ]);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
