<?php

return [

    /*
    | The account whose (verified) email matches this address becomes the
    | Super Admin. Two-factor authentication is required before /admin opens.
    */
    'super_admin_email' => env('SUPER_ADMIN_EMAIL'),

    /*
    | Current versions of the legal documents. Bump a version when the text
    | changes; every user is asked to accept again on their next visit.
    */
    'terms_version' => '1.0',
    'privacy_version' => '1.0',
    'cookie_version' => '1.0',
    'legal_updated' => '2026-10-27',

    /*
    | Optional, privacy-friendly analytics. Nothing loads unless BOTH an ID is
    | set here and the visitor accepted the "analytics" cookie category.
    */
    'analytics' => [
        'id' => env('ANALYTICS_ID'),
        'src' => env('ANALYTICS_SRC', 'https://plausible.io/js/script.js'),
    ],

    // Shared hosting has no Supervisor: let the scheduler drain the queue every minute.
    'queue_via_scheduler' => (bool) env('QUEUE_VIA_SCHEDULER', true),

    // Public share page shows this many months ahead.
    'share_months' => 3,

    // Longest date range that can be filed in one go.
    'max_range_days' => 62,

    // Retention (scheduled `sinowala:prune`).
    'retention' => [
        'unverified_days' => 30,
        'contact_days' => 365,
        'audit_days' => 730,
        'consent_days' => 730,
        'privacy_request_days' => 730,
    ],

    // Shown on legal pages until you replace them in .env.
    'business' => [
        'name' => env('BUSINESS_NAME', '[PLACEHOLDER: pangalan ng negosyo]'),
        'address' => env('BUSINESS_ADDRESS', '[PLACEHOLDER: address ng negosyo]'),
        'email' => env('BUSINESS_EMAIL', '[PLACEHOLDER: contact email]'),
        'dpo_name' => env('DPO_NAME', '[PLACEHOLDER: pangalan ng Data Protection Officer]'),
        'dpo_email' => env('DPO_EMAIL', '[PLACEHOLDER: email ng DPO]'),
    ],

    // Comma-separated proxy IPs, or * (behind Cloudflare / a load balancer).
    'trusted_proxies' => env('TRUSTED_PROXIES'),
];
