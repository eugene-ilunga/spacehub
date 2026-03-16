<?php

return [
  // dashboard route
  'dashboard' => [
    'route' => 'admin.dashboard'
  ],

  // space management route
  'space_management_group' => [
    'routes' => [
      'admin.space_management.*',
      'admin.location_management.*',
      'admin.feature_record.*',
      'admin.space-management.*',
      'admin.holiday.*',
      'admin.manage_schedule.*',
      'admin.manage_weekend.index',

      'vendor.space_management.*',
      'vendor.holiday.*',
      'vendor.manage_weekend.index',
      'vendor.manage_schedule.*',
    ]
  ],
  'space_settings' => [
    'route' => 'admin.space-management.space.settings'
  ],
  'holidays' => [
    'routes' => [
      'admin.holiday.select_vendor',
      'admin.holiday.index',
      'vendor.holiday.select_vendor',
      'vendor.holiday.index',
    ]
  ],
  'coupons' => [
    'routes' => [
      'admin.space_management.coupons.index',
      'vendor.space_management.coupons.index'
    ]
  ],
  'specifications' => [
    'routes' => [
      'admin.space_management.amenities.index',
      'admin.location_management.country.index',
      'admin.location_management.state.index',
      'admin.location_management.city.index',
      'admin.space_management.space-category.index',
      'admin.space_management.sub-category.index'
    ]
  ],
  'amenities' => [
    'route' => 'admin.space_management.amenities.index'
  ],
  'locations' => [
    'routes' => [
      'admin.location_management.country.index',
      'admin.location_management.state.index',
      'admin.location_management.city.index'
    ]
  ],
  'countries' => [
    'route' => 'admin.location_management.country.index'
  ],
  'states' => [
    'route' => 'admin.location_management.state.index'
  ],
  'cities' => [
    'route' => 'admin.location_management.city.index'
  ],
  'categories' => [
    'route' => 'admin.space_management.space-category.index'
  ],
  'subcategories' => [
    'route' => 'admin.space_management.sub-category.index'
  ],
  'featured_management' => [
    'routes' => ['admin.feature_record.*']
  ],
  'featured_charges' => [
    'route' => 'admin.feature_record.charge.index'
  ],
  'all_featured_requests' => [
    'route' => 'admin.feature_record.index'
  ],
  'pending_featured_requests' => [
    'route' => 'admin.feature_record.index?feature_status=pending'
  ],
  'approved_featured_requests' => [
    'route' => 'admin.feature_record.index?feature_status=approved'
  ],
  'rejected_featured_requests' => [
    'route' => 'admin.feature_record.index?feature_status=rejected'
  ],
  'forms' => [
    'routes' => [
      'admin.space-management.form.index',
      'admin.space-management.form.input',
      'admin.space-management.form.edit_input',
      'vendor.space_management.form.index',
      'vendor.space_management.form.input',
      'vendor.space_management.form.edit_input',
    ]
  ],
  'spaces' => [
    'routes' => [
      'admin.space_management.space.index',
      'admin.space_management.space.create',
      'admin.space_management.space.edit',
      'admin.space_management.seller_select',
      'admin.space_management.service.view_services',
      'admin.space_management.service.partial_create',
      'admin.space_management.service.edit',
      'admin.space_management.space.select_space_type',
      'admin.manage_schedule.time_slot.index',
      'admin.manage_weekend.index',
      'admin.manage_schedule.time_slot.manage_time_slot',

      'vendor.space_management.space.index',
      'vendor.space_management.space.create',
      'vendor.space_management.space.edit',
      'vendor.manage_schedule.time_slot.index',
      'vendor.manage_schedule.time_slot.manage_time_slot',
      'vendor.space_management.service.create_under_space',
      'vendor.space_management.service.view_from_space',
      'vendor.space_management.service.edit',
      'vendor.space_management.space.select_space_type',
      'vendor.manage_weekend.index',
    ]
  ],

  // bookings and request
  'bookings_requests_group' => [
    'routes' => [
      'admin.space.form.get_quote.*',
      'admin.space.form.tour_request.*',
      'admin.booking_record.*',
      'admin.add_booking.*',

      'vendor.space.form.get_quote.*',
      'vendor.space.form.tour_request.*',
      'vendor.booking_record.*',
      'vendor.add_booking.*',
    ]
  ],

  'quote_requests' => [
    'routes' => [
      'admin.space.form.get_quote.*',
      'vendor.space.form.get_quote.*'
    ]
  ],

  'all_quote_requests' => [
    'routes' => [
      'admin.space.form.get_quote.index',
      'vendor.space.form.get_quote.index'
    ]
  ],

  'pending_quote_requests' => [
    'routes' => [
      'admin.space.form.get_quote.index?quote_status=pending',
      'vendor.space.form.get_quote.index?quote_status=pending'
    ]
  ],
  'responded_quote_requests' => [
    'routes' => [
      'admin.space.form.get_quote.index?quote_status=responded',
      'vendor.space.form.get_quote.index?quote_status=responded'
    ]
  ],
  'in_progress_quote_requests' => [
    'routes' => [
      'admin.space.form.get_quote.index?quote_status=in_progress',
      'vendor.space.form.get_quote.index?quote_status=in_progress'
    ]
  ],
  'closed_quote_requests' => [
    'routes' => [
      'admin.space.form.get_quote.index?quote_status=closed',
      'vendor.space.form.get_quote.index?quote_status=closed'
    ]
  ],
  'cancelled_quote_requests' => [
    'routes' => [
      'admin.space.form.get_quote.index?quote_status=cancelled',
      'vendor.space.form.get_quote.index?quote_status=cancelled'
    ]
  ],
  'tour_requests' => [
    'routes' => [
      'admin.space.form.tour_request.*',
      'vendor.space.form.tour_request.*'
    ]
  ],
  'all_tour_requests' => [
    'routes' => [
      'admin.space.form.tour_request.index',
      'vendor.space.form.tour_request.index'
    ]
  ],
  'pending_tour_requests' => [
    'routes' => [
      'admin.space.form.tour_request.index?tour_status=pending',
      'vendor.space.form.tour_request.index?tour_status=pending'
    ]
  ],
  'confirmed_tour_requests' => [
    'routes' => [
      'admin.space.form.tour_request.index?tour_status=confirmed',
      'vendor.space.form.tour_request.index?tour_status=confirmed'
    ]
  ],
  'closed_tour_requests' => [
    'routes' => [
      'admin.space.form.tour_request.index?tour_status=closed',
      'vendor.space.form.tour_request.index?tour_status=closed'
    ]
  ],
  'cancelled_tour_requests' => [
    'routes' => [
      'admin.space.form.tour_request.index?tour_status=cancelled',
      'vendor.space.form.tour_request.index?tour_status=cancelled'
    ]
  ],
  'booking_management' => [
    'routes' => [
      'admin.booking_record.*',
      'admin.add_booking.*',
      'vendor.booking_record.*',
      'vendor.add_booking.*',
    ]
  ],
  'add_booking' => [
    'routes' => [
      'admin.add_booking.space_selection',
      'admin.add_booking.index',
      'vendor.add_booking.space_selection',
      'vendor.add_booking.index',
    ]
  ],
  'all_bookings' => [
    'routes' => [
      'admin.booking_record.index',
      'admin.booking_record.show',
      'vendor.booking_record.index',
      'vendor.booking_record.show',
    ]
  ],
  'pending_bookings' => [
    'routes' => [
      'admin.booking_record.index?booking_status=pending',
      'vendor.booking_record.index?booking_status=pending'
    ]
  ],
  'approved_bookings' => [
    'routes' => ['admin.booking_record.index?booking_status=approved', 'vendor.booking_record.index?booking_status=approved']
  ],
  'rejected_bookings' => [
    'routes' => ['admin.booking_record.index?booking_status=rejected', 'vendor.booking_record.index?booking_status=rejected']
  ],
  'booking_report' => [
    'routes' => ['admin.booking_record.booking_report', 'vendor.booking_record.booking_report']
  ],

  // user management 

  'user_management_group' => [
    'routes' => [
      'admin.user_management.registered_users',
      'admin.user_management.user.details',
      'admin.user_management.user.edit',
      'admin.user_management.user.change_password',
      'admin.user_management.subscribers',
      'admin.user_management.mail_for_subscribers',
      'admin.user_management.registered_users.setting',

    ]
  ],
  'user_settings' => [
    'route' => 'admin.user_management.registered_users.setting'
  ],
  'registered_users' => [
    'routes' => [
      'admin.user_management.registered_users',
      'admin.user_management.user.details',
      'admin.user_management.user.edit',
      'admin.user_management.user.change_password'
    ]
  ],
  'subscribers' => [
    'routes' => [
      'admin.user_management.subscribers',
      'admin.user_management.mail_for_subscribers'
    ]
  ],
  // vendor management
  'vendors_management_group' => [
    'routes' => [
      'admin.end-user.vendor.registered_vendor',
      'admin.end-user.vendor.add',
      'admin.end-user.vendor.details',
      'admin.end-user.vendor.edit',
      'admin.end-user.vendor.settings',
      'admin.end-user.vendor.change_password'
    ]
  ],
  'vendor_settings' => [
    'route' => 'admin.end-user.vendor.settings'
  ],
  'registered_vendors' => [
    'routes' => [
      'admin.end-user.vendor.registered_vendor',
      'admin.end-user.vendor.details',
      'admin.end-user.vendor.edit',
      'admin.end-user.vendor.change_password'
    ]
  ],
  'add_vendor' => [
    'route' => 'admin.end-user.vendor.add'
  ],

  //subscriptions management
  'subscriptions_management_group' => [
    'routes' => [
      'admin.package.*',
      'admin.payment-log.index'
    ]
  ],
  'package_management' => [
    'routes' => ['admin.package.*']
  ],
  'package_settings' => [
    'route' => 'admin.package.settings'
  ],
  'package_features' => [
    'route' => 'admin.package.features'
  ],
  'packages_list' => [
    'routes' => [
      'admin.package.index',
      'admin.package.edit'
    ]
  ],
  'subscription_logs' => [
    'route' => 'admin.payment-log.index'
  ],

  // withdrawal management 
  'withdrawal_management_group' => [
    'routes' => [
      'admin.withdraw.payment_method',
      'admin.withdraw_payment_method.mange_input',
      'admin.withdraw_payment_method.edit_input',
      'admin.withdraw.withdraw_request'
    ]
  ],
  'payment_methods' => [
    'routes' => [
      'admin.withdraw.payment_method',
      'admin.withdraw_payment_method.mange_input'
    ],
  ],
  'withdraw_requests' => [
    'route' => 'admin.withdraw.withdraw_request',
  ],

  // pages route
  'pages_group' => [
    'routes' => [
      'admin.home_page.section_content',
      'admin.home_page.section_customization',
      'admin.home_page.work_process_section',
      'admin.blog_management.categories',
      'admin.blog_management.posts',
      'admin.blog_management.edit_post',
      'admin.custom_pages',
      'admin.custom_pages.edit_page',
      'admin.faq_management',
      'admin.home_page.about_section',
      'admin.footer.content',
      'admin.footer.quick_links',
      'admin.blog_management.create_post',
      'admin.home_page.testimonials_section',
      'admin.home_page.contact.index',
      'admin.about_us.about_content.index',
      'admin.breadcrumb.settings',
      'admin.breadcrumb.image',
      'admin.menu_builder',
      'admin.basic_settings.seo',
      'admin.basic_settings.page_headings',
      'admin.custom_pages.create_page',
      'admin.home_page.additional_sections.*',
      'admin.home_page.additional_section.edit',
    ]
  ],
  'home_page' => [
    'routes' => [
      'admin.home_page.section_content',
      'admin.home_page.section_customization',
      'admin.home_page.work_process_section',
      'admin.home_page.testimonials_section',
      'admin.home_page.additional_sections.*',
      'admin.home_page.additional_section.edit',
    ]
  ],
  'section_customization' => [
    'route' => 'admin.home_page.section_customization'
  ],
  'section_content' => [
    'route' => 'admin.home_page.section_content'
  ],
  'work_process_section' => [
    'route' => 'admin.home_page.work_process_section'
  ],
  'testimonials_section' => [
    'route' => 'admin.home_page.testimonials_section'
  ],
  'additional_sections' => [
    'routes' => [
      'admin.home_page.additional_sections.index',
      'admin.home_page.additional_sections.create',
      'admin.home_page.additional_section.edit',
    ]
  ],
  'sections' => [
    'routes' => [
      'admin.home_page.additional_sections.index',
      'admin.home_page.additional_section.edit'
    ]
  ],
  'about_us' => [
    'routes' => [
      'admin.home_page.about_section',
      'admin.about_us.about_content.index'
    ]
  ],
  'about_section' => [
    'route' => 'admin.home_page.about_section'
  ],
  'about_content' => [
    'route' => 'admin.about_us.about_content.index'
  ],
  'menu_builder' => [
    'route' => 'admin.menu_builder'
  ],
  'footer' => [
    'routes' => [
      'admin.footer.content',
      'admin.footer.quick_links'
    ]
  ],
  'footer_content' => [
    'route' => 'admin.footer.content'
  ],
  'footer_quick_links' => [
    'route' => 'admin.footer.quick_links'
  ],
  'breadcrumb' => [
    'routes' => [
      'admin.breadcrumb.settings',
      'admin.breadcrumb.image',
      'admin.basic_settings.page_headings'
    ]
  ],
  'breadcrumb_image' => [
    'route' => 'admin.breadcrumb.image'
  ],
  'page_headings' => [
    'route' => 'admin.basic_settings.page_headings'
  ],
  'faqs' => [
    'route' => 'admin.faq_management'
  ],
  'blog' => [
    'routes' => [
      'admin.blog_management.categories',
      'admin.blog_management.posts',
      'admin.blog_management.create_post',
      'admin.blog_management.edit_post'
    ]
  ],
  'blog_categories' => [
    'route' => 'admin.blog_management.categories'
  ],
  'blog_posts' => [
    'routes' => [
      'admin.blog_management.posts',
      'admin.blog_management.create_post',
      'admin.blog_management.edit_post'
    ]
  ],
  'contact_page' => [
    'route' => 'admin.home_page.contact.index'
  ],
  'custom_pages' => [
    'routes' => [
      'admin.custom_pages',
      'admin.custom_pages.create_page',
      'admin.custom_pages.edit_page'
    ]
  ],
  'seo_information' => [
    'route' => 'admin.basic_settings.seo'
  ],
  // shop management
  'shop_management_group' => [
    'routes' => [
      'admin.shop_management.tax_amount',
      'admin.shop_management.shipping_charges',
      'admin.shop_management.coupons',
      'admin.shop_management.product.categories',
      'admin.shop_management.products',
      'admin.shop_management.select_product_type',
      'admin.shop_management.create_product',
      'admin.shop_management.edit_product',
      'admin.shop_management.orders',
      'admin.shop_management.order.details',
      'admin.shop_management.settings',
      'admin.shop_management.report'
    ]
  ],
  'shop_settings' => [
    'route' => 'admin.shop_management.settings'
  ],
  'tax_amounts' => [
    'route' => 'admin.shop_management.tax_amount'
  ],
  'shipping_charges' => [
    'route' => 'admin.shop_management.shipping_charges'
  ],
  'shop_coupons' => [
    'route' => 'admin.shop_management.coupons'
  ],
  'manage_products' => [
    'routes' => [
      'admin.shop_management.product.categories',
      'admin.shop_management.products',
      'admin.shop_management.select_product_type',
      'admin.shop_management.create_product',
      'admin.shop_management.edit_product'
    ]
  ],
  'product_categories' => [
    'route' => 'admin.shop_management.product.categories'
  ],
  'products_list' => [
    'routes' => [
      'admin.shop_management.products',
      'admin.shop_management.select_product_type',
      'admin.shop_management.create_product',
      'admin.shop_management.edit_product'
    ]
  ],
  'orders' => [
    'routes' => [
      'admin.shop_management.orders',
      'admin.shop_management.order.details'
    ]
  ],
  'shop_report' => [
    'route' => 'admin.shop_management.report'
  ],

  //transaction route
  'transactions' => [
    'route' => 'admin.dashboard.transaction'
  ],

  // support ticket route
  'support_tickets_group' => [
    'routes' => [
      'admin.support_tickets',
      'admin.support_ticket.conversation'
    ]
  ],
  'all_support_tickets' => [
    'routes' => [
      'admin.support_tickets',
      'admin.support_ticket.conversation'
    ],
  ],
  'pending_support_tickets' => [
    'route' => 'admin.support_tickets?ticket_status=pending'
  ],
  'open_support_tickets' => [
    'route' => 'admin.support_tickets?ticket_status=open'
  ],
  'closed_support_tickets' => [
    'route' => 'admin.support_tickets?ticket_status=closed'
  ],
  'support_ticket_conversation' => [
    'route' => 'admin.support_ticket.conversation'
  ],

  //advertisement route
  'advertisements_group' => [
    'routes' => [
      'admin.advertise.settings',
      'admin.advertise.all_advertisement'
    ]
  ],
  'advertise_settings' => [
    'route' => 'admin.advertise.settings'
  ],
  'all_advertisements' => [
    'route' => 'admin.advertise.all_advertisement'
  ],

  //announcement popup route

  'announcement_popups_group' => [
    'routes' => [
      'admin.announcement_popups',
      'admin.announcement_popups.select_popup_type',
      'admin.announcement_popups.create_popup',
      'admin.announcement_popups.edit_popup'
    ]
  ],

  //settings routes
  'settings_group' => [
    'routes' => [
      'admin.basic_settings.general_settings',
      'admin.basic_settings.mail_from_admin',
      'admin.basic_settings.mail_to_admin',
      'admin.basic_settings.mail_templates',
      'admin.basic_settings.edit_mail_template',
      'admin.basic_settings.plugins',
      'admin.basic_settings.maintenance_mode',
      'admin.basic_settings.cookie_alert',
      'admin.payment_gateways.online_gateways',
      'admin.payment_gateways.offline_gateways',
      'admin.language_management',
      'admin.language_management.edit_keyword',
      'admin.language_management.admin.edit_keyword',
      'admin.language_management.settings',
      'admin.basic_settings.social_medias',
    ]
  ],
  'general_settings' => [
    'route' => 'admin.basic_settings.general_settings'
  ],
  'email_settings_group' => [
    'routes' => [
      'admin.basic_settings.mail_from_admin',
      'admin.basic_settings.mail_to_admin',
      'admin.basic_settings.mail_templates',
      'admin.basic_settings.edit_mail_template'
    ]
  ],
  'mail_from_admin' => [
    'route' => 'admin.basic_settings.mail_from_admin'
  ],
  'mail_to_admin' => [
    'route' => 'admin.basic_settings.mail_to_admin'
  ],
  'mail_templates' => [
    'routes' => [
      'admin.basic_settings.mail_templates',
      'admin.basic_settings.edit_mail_template'
    ]
  ],
  'payment_gateways_group' => [
    'routes' => [
      'admin.payment_gateways.online_gateways',
      'admin.payment_gateways.offline_gateways'
    ]
  ],
  'online_gateways' => [
    'route' => 'admin.payment_gateways.online_gateways'
  ],
  'offline_gateways' => [
    'route' => 'admin.payment_gateways.offline_gateways'
  ],
  'language_management_group' => [
    'routes' => [
      'admin.language_management',
      'admin.language_management.edit_keyword',
      'admin.language_management.admin.edit_keyword',
      'admin.language_management.settings'
    ]
  ],
  'language_settings' => [
    'route' => 'admin.language_management.settings'
  ],
  'languages' => [
    'routes' => [
      'admin.language_management',
      'admin.language_management.edit_keyword',
      'admin.language_management.admin.edit_keyword'
    ]
  ],
  'plugins' => [
    'route' => 'admin.basic_settings.plugins'
  ],
  'maintenance_mode' => [
    'route' => 'admin.basic_settings.maintenance_mode'
  ],
  'cookie_alert' => [
    'route' => 'admin.basic_settings.cookie_alert'
  ],
  'social_media' => [
    'route' => 'admin.basic_settings.social_medias'
  ],

  // staff management
  'staffs_management_group' => [
    'routes' => [
      'admin.admin_management.role_permissions',
      'admin.admin_management.role.permissions',
      'admin.admin_management.registered_admins'
    ]
  ],
  'role_permissions' => [
    'routes' => [
      'admin.admin_management.role_permissions',
      'admin.admin_management.role.permissions'
    ]
  ],
  'registered_staffs' => [
    'route' => 'admin.admin_management.registered_admins'
  ],

  // this data for vendor dashbaord
  'subscription' => [
    'routes' => [
      'vendor.plan.extend.index',
      'vendor.plan.extend.checkout',
      'vendor.subscription_log',
    ]
  ],

  'buy_plan' => [
    'routes' => [
      'vendor.plan.extend.index',
      'vendor.plan.extend.checkout',
    ]
  ],

  'subscription_log' => [
    'route' => 'vendor.subscription_log'
  ],

  'withdrawals' => [
    'routes' => [
      'vendor.withdraw',
      'vendor.withdraw.create'
    ]
  ],

  'support_ticket' => [
    'routes' => [
      'vendor.support_ticket.message',
      'vendor.support_ticket.create',
      'vendor.support_ticket'
    ]
  ],
];
