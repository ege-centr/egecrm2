<?php

use Illuminate\Database\Seeder;
use App\Models\Sms\SmsTemplateCode;

class SmsTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sms_templates')->insert([
            [
                'title' => 'доступен новый отчёт',
                'text' => '{parent_name}, в ЛК доступен новый отчёт',
                'type' => 'auto',
                'description' => "
                    <p>Отправляется родителям, когда когда в ЛК появляется новый доступный отчёт</p>
                    <ul>
                        <li>
                            <pre>{parent_name}</pre> – имя родителя
                        </li>
                    </ul>
                ",
                'code' => SmsTemplateCode::REPORT_BECAME_AVAILABLE,
            ]
        ]);
    }
}
