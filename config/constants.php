<?php

return [
    "sagepay_vendor_id" => "togiveback",
    "sagepay_Referrer_id" => "3F7A4119-8671-464F-A091-9E59EB47B80C",
    "sagepay_test_mode" => true,
    "sagepay_gateway_server" => "SagePay\Direct",
    "sagepay_admin_percentage" => 10,
    "financial_year_start" => 2018,
    "static_url" => 'https://churchapp.goodtogive.co.uk/',
    "transition_position" => '1',
    "background_color" => '#a8d57e',
    'DEEPLINKING' =>  ['APP' => 'com.acquaint.churchapp',
                        'IOSAPPSTORE'=>'com.acquaint.churchapp',
                        'ANDROIDAPPSTORE'=>'com.acquaint.churchapp',
                        'WEBSITE'=>'vedioDetail?v=19'],
    "notification_type_data" => [
        "update_taks_assign" =>[
            "title" =>"Task completed",
            "body" =>"##USER_NAME## has completed ##TASK_TITLE##.",
        ],
         "update_taks_assign_accepted" =>[
            "title" =>"Task accepted",
            "body" =>"##USER_NAME## has accepted ##TASK_TITLE##.",
        ],
         "update_taks_assign_rejected" =>[
            "title" =>"Task rejected",
            "body" =>"##USER_NAME## has rejected ##TASK_TITLE##.",
        ],
        "create_task_by_church" => [
            "title" =>"Task created by church",
            "body" =>"##CHURCH_NAME## has created a task for you.",
        ],
         "update_task_by_church" => [
            "title" =>"Task updated by church",
            "body" =>"##CHURCH_NAME## has updated your task.",
        ],
        "create_group_by_church" => [
            "title" =>"Church created a new group",
            "body" =>"##CHURCH_NAME## has added you in a group.",
        ],
        "update_declaration_form" => [
            "title" =>"Declaration Updated",
            "body" =>"GiftAid declaration has been updated.",
        ],
        "approve_member" => [
            "title" =>"Member account approved",
            "body" =>"Member account has been approved.",
        ],
        "reject_member" => [
            "title" =>"Member acount rejected",
            "body" =>"Member account has been rejected.",
        ],
        "approve_user_member" => [
            "title" =>"Member account approved",
            "body" =>"Member account has been approved.",
        ],
        "reject_user_member" => [
            "title" =>"Member acount rejected",
            "body" =>"Member account has been rejected.",
        ],
        "member_registration" => [
            "title" =>"New member registered",
            "body" =>"Approve or reject new member registration.",
        ],
        "delete_inactive_account" => [
            "title" =>"User deactive or delete",
            "body" =>"User has been deactive or delete ",
        ],
        "switch_user_role" => [
            "title" =>"Role changed",
            "body" =>"Your role has been changed",
        ],
        "change_primary_user_status" => [
            "title" =>"Primary user status changed",
            "body" =>"Primary user status has been changed",
        ],
        "update_scripture" => [
            "title" =>"Church update scripture",
            "body" =>"Church scripture has been changed",
        ],
        "repeat_donation_stop" => [
            "title" =>"Project donation stop",
            "body" =>"Project repeat donation has been stop",
        ],

    ],

    ];
