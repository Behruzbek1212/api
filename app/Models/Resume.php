<?php

namespace App\Models;

use App\Traits\ApiLogActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $file
 * @property array $data
 * -------------- Relationships --------------
 * @property User $user
 */
class Resume extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ApiLogActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'file',
        'data'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'experience',
        'percentage'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Display the user profile
     *
     * @return BelongsTo
     * @see https://laravel.com/docs/9.x/eloquent-relationships#one-to-many-inverse
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate experience time
     *
     * @param array $data
     * @return int
     */
    public function calculate_experience(array $data): int
    {
        $employments = $data['employment'] ;
        $exp_time = 0;

        $unique_employments = [];

        foreach ($employments as $employment) {
            $key = $employment["date"]["start"]["year"] . "-" . $employment["date"]["end"]["year"];

            if (!array_key_exists($key, $unique_employments)) {
                $unique_employments[$key] = $employment;
            }
        }

        $output = [];

        foreach ($unique_employments as $employment) {
            $output[] = [
                "title" => $employment["title"],
                "employer" => $employment["employer"],
                "description" => $employment["description"],
                "date" => $employment["date"],
            ];
        }

        for ($i = 0; $i < count($output); $i++) {
            $employment = $output[$i];

            $start_year = $employment['date']['start']['year'] * 1;
            $start_month = $employment['date']['start']['month'] * 1;

            $end_year = $employment['date']['end']['year'] * 1 ?? 0;
            $end_month = $employment['date']['end']['month'] * 1 ?? 0;

            if (@$employment['date']['present'] === true) {
                $end_year = date('Y');
                $end_month = date('m');
            }

            $exp_time += ($end_year - $start_year) * 12;
            $exp_time += $end_month - $start_month;
        }

        return $exp_time;
    }


    /**
     * Calculate percentage
     *
     * @param array $data
     * @return int
     */

     public function calculate_percentage($data)
     {
        $resume = $data;
        $resumeData = json_decode($resume['data'],JSON_PRETTY_PRINT);
        $candidate = Candidate::where('user_id', $resume['user_id'])->first();
        $user =  User::where('id', $resume['user_id'])->first();
        $resumBalls = DB::table('resume_balls')->get();

        $candidateData =  [];
        $candidateData['education_level'] = $candidate['education_level'] ?? null;
        $candidateData['languages'] = $candidate['languages'] ?? null;
        $candidateData['specialization'] = $candidate['specialization'] ?? null;
        $candidateData['address'] = $candidate['address'] ?? null;


        $filled_fields = 0;
        $ball = [];
        $max_ball = 0;

        foreach($resumBalls as $resumBall){
            $ball[$resumBall->name] =  intval($resumBall->ball);
            $max_ball += intval($resumBall->ball);
        }


        foreach ($candidateData as $key => $value) {

            if ($value !== null && $value !== '' && $value !== [] ) {
                $filled_fields += $ball[$key];
            }
        }

        // dd($resumeData);
        foreach ($resumeData as $key => $value) {
            if($value !== '' && $value !== null && $value !== []) {
              switch($key){
                case 'about':
                        if(strlen($value) > 100){
                            $filled_fields += $ball[$key];
                        } else {
                            $filled_fields += 5;
                        }
                    break;
                case 'position' :
                        $filled_fields += $ball[$key];
                    break;
                case 'employment' :
                        $filled_fields += $ball[$key];
                    break;
                case 'sphere' :
                        $filled_fields += $ball[$key];
                    break;
                case 'salary' :
                        $filled_fields += $ball[$key];
                    break;
                case 'location' :
                        $filled_fields += $ball[$key];
                    break;
                case 'work_type' :
                        $filled_fields += $ball[$key];
                    break;
                case 'computer_skills' :
                        $filled_fields += $ball[$key];
                    break;
                case 'additional_education' :
                        $filled_fields += $ball[$key];
                    break;
                case 'education' :
                        $filled_fields += $ball[$key];
                    break;
                case 'skills' :
                       $filled_fields += $ball[$key];
                    break;
                case 'links':
                     if($resumeData[$key]['other'] !== null || $resumeData[$key]['gitHub'] !== null ||
                      $resumeData[$key]['behance'] !== null || $resumeData[$key]['linkedin'] !== null ||
                      $resumeData[$key]['telegram'] !== null || $resumeData[$key]['whatsapp'] !== null ||  $resumeData[$key]['instagram'] !== null  )
                     {
                        $filled_fields += $ball[$key];
                     }
                    break;

                }
               }
            }

            $percentage = ($filled_fields * 100) / $max_ball;

            return $percentage;
         }





    /**
     * Append `experience` column
     *
     * @return Attribute
     */
    public function experience(): Attribute
    {
        return Attribute::get(fn ($_val, $attr) =>
            $this->calculate_experience(json_decode($attr['data'], JSON_PRETTY_PRINT))
        );
    }

    /**
     * Append percentage column
     *
     * @return Attribute
     */

     public function percentage(): Attribute
     {
         return Attribute::get(fn ($_val, $attr) =>
             $this->calculate_percentage($attr)
         );
     }
}
