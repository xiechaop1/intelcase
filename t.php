<?php

$r = [
		[['avatar', 'uname', 'mobile', 'email',
				'birthday', 'work_start_date', 'current_company',
				'current_post', 'foreign_language', 'university', 'job_status', 'expect_post',
				'document_file', 'expect_salary', 'resource', 'expect_career',
				'skills', 'certification', 'self_evaluation', 'interview',
				'education_experience', 'work_experience', 'project_experience',
				'education', 'national', 'language_skills', 'training',
				'nation_group', 'birthplace', 'census', 'marry', 'political', 'gender',
				'fertility', 'work_years', 'user_no', 'city_str', 'expect_city_str'], 'string'],
		[['current_salary', 'other_income'], 'string'],
		[['city_id', 'age',  'expect_industry', 'current_industry', 'document_status', 'created_at', 'updated_at', 'status'], 'integer'],
];

foreach ($r as $one) {
	foreach ($one[0] as $col) {
		echo 'public $' . $col . ";\n";
	}
}
