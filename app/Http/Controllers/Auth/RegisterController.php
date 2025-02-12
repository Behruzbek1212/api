<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Bitrix\BitrixController;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Customer;
use App\Models\User;
use App\Services\BitrixService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use JsonException;

class RegisterController extends Controller
{
    protected Builder $model;

    /**
     * Controller constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = DB::table('password_verification');
    }

    /**
     * Registration of new users
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception | JsonException
     */
    public function register(Request $request): JsonResponse
    {

        $request->validate([
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,customer,candidate'],
            'email' => ['email', 'unique:users,email']
        ]);

        // dd($request->all());
        /** @var User $user */
        $user = User::query()->create([
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ]);

        match ($user->role) {
            'candidate' =>
            $this->registerCandidate($request, $user),

            'customer' =>
            $this->registerCustomer($request, $user),

            default =>
            throw new Exception('Invalid role')
        };
        $user->markPhoneAsVerified();
        $token = $user->createToken($user->name . '-' . Hash::make($user->id))
            ->plainTextToken;



        return response()->json([
            'status' => true,
            'message' => 'User successfully registered',
            'user' => User::query()
                ->with('customer', 'candidate')
                ->find($user->id),
            'token' => $token
        ]);
    }

    /**
     * Registration of new roles
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function role(Request $request): JsonResponse
    {
        match ($request->user()->role) {
            'candidate' =>
            $this->registerCandidate($request, $request->user()),

            'customer' =>
            $this->registerCustomer($request, $request->user()),

            default =>
            throw new Exception('Invalid role')
        };

        return response()->json([
            'status' => true,
            'message' => 'User successfully registered',
            'user' => User::query()
                ->with('customer', 'candidate')
                ->find($request->user()->id),
        ]);
    }

    /**
     * Register a customer user
     *
     * @param  Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function registerCustomer(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string'],
            'avatar' => ['nullable', 'string'],
            'owned_date' => ['required', 'date'],
            'location' => ['required', 'string'],
            'address' => ['required', 'string'],
            'about' => ['string', 'nullable'],
            'limit_id' => ['integer', 'nullable'],
            'limit_start_day' => ['date', 'nullable'],
            'limit_end_day' => ['date', 'nullable']
        ]);
        $check = Customer::where('user_id' ,  $user->id)->where('deleted_at', null)->first();
        if($check == null) {
            $customer = $user->customer()->create([
                'name' => $request->input('name'),
                'about' => $request->input('about'),
                'avatar' => $request->input('avatar') ?? null,
                'owned_date' => $request->input('owned_date'),
                'location' => $request->input('location'),
                'address' => $request->input('address'),
                'active' => true,
                'limit_id' => $request->limit_id ?? null,
                'limit_start_day' => $request->limit_start_day ?? null,
                'limit_end_day' => $request->limit_end_day ?? null
            ]);
        } else {
            $customer = $user->customer()->first();
        }


        $message = "🆕 <b>Yangi kompaniya</b>\n";
        $message .= "🏢 Kompaniya: <b>" . $request->name . "</b>\n";
        $message .= "📞 Telefon raqam: " . $user->phone . "\n\n";

        Http::withoutVerifying()->post("https://api.telegram.org/bot5777417067:AAGvh21OUGVQ7nmSnLbIhzTiZxoyMQMIZKk/sendMessage", [
            'chat_id' => '-1001873253638',
            'text' => $message,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    [
                        'text' => "↗️ Kompaniyani ko'rish",
                        'url' => 'https://jobo.uz/company/' . $customer->id
                    ]
                ]]
            ])
        ]);

        $data = User::query()
            ->with('candidate')
            ->find($user->id);
        $response = Http::post('https://jobo-uz.bitrix24.ru/rest/1/tl9n71594xu4o6tt/crm.lead.add.json', [
            "fields" => array(
                "TITLE" => $data->phone,
                "NAME" => $user->customer?->name ?? null,
                "PHONE" => [array("VALUE" => $data->phone, "VALUE_TYPE" => "WORK")],
                "ADDRESS_CITY" =>  $data->customer?->address ?? null,
                "SOURCE_ID" => 'web site',
                "ASSIGNED_BY_ID" => 1,
                "STATUS_ID" => "NEW",
                "OPENED" => "Y",
            ),
        ]);

        return response()->json($customer);
    }

    /**
     * Register a candidate account
     *
     * @param  Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function registerCandidate(Request $request, User $user)
    {

        $request->validate([
            'name' => ['required', 'string'],
            'surname' => ['nullable', 'string'],
            'sex' => ['string', 'in:male,female', 'nullable'],
            'education_level' => ['string', 'nullable'],
            'birthday' => ['required', 'date'],
            'languages' => ['nullable'],
            'spheres' => ['nullable'],
            'address' => ['required', 'string']
        ]);
        $check = Candidate::where('user_id' ,  $user->id)->where('deleted_at', null)->first();
        if($check == null){
            $candidate = $user->candidate()->create([
                'avatar' => $request->input('avatar') ?? null,
                'name' => $request->input('name'),
                'surname' => $request->input('surname') ?? null,
                'sex' => $request->input('sex') ?? 'male' ?? null,
                'spheres' => $request->input('spheres') ?? null,
                'education_level' => $request->input('education_level') ?? null,
                'specialization' => $request->input('specialization') ?? null,
                'languages' => $request->input('languages') ?? null,
                'birthday' => $request->input('birthday'),
                'address' => $request->input('address'),
                'test' => null,
                'services' => null,
                'active' => true
            ]);
        } else {
            $candidate = $user->candidate()->first();
        }


        $data = User::query()
            ->with('candidate')
            ->find($user->id);
        $response = Http::post('https://jobo-uz.bitrix24.ru/rest/1/tl9n71594xu4o6tt/crm.lead.add.json', [
            "fields" => array(
                "TITLE" => $data->phone,
                "NAME" => $user->candidate?->name ?? null,
                "LAST_NAME" => $data->candidate->surname ?? "",
                "PHONE" => [array("VALUE" => $data->phone, "VALUE_TYPE" => "WORK")],
                "ADDRESS_CITY" =>  $data->candidate?->address ?? null,
                "SOURCE_ID" => 'web site',
                "ASSIGNED_BY_ID" => 1,
                "STATUS_ID" => "NEW",
                "OPENED" => "Y",
                // "SPHERES" => $data->candidate?->spheres ?? null,
                // "SPECIALIZATION" => $data->candidate?->specialization ?? null,
                // "LANGUAGES" => $data->candidate?->languages ?? null,
            ),
        ]);

        return response()->json($candidate);
    }
}
