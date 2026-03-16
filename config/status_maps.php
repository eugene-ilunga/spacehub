<?php

return [
  'tour_requests' => [
    '' => [
      'title' => 'All Requests',
      'dropdown' => 'All',
      'label' => 'All',
      'badge' => '',
      'select_class' => '',
    ],
    'pending' => [
      'title' => 'Pending Requests',
      'dropdown' => 'Pending',
      'label' => 'Pending',
      'badge' => 'badge-warning',
      'select_class' => 'bg-warning text-dark',
    ],
    'confirmed' => [
      'title' => 'Confirmed Requests',
      'dropdown' => 'Confirmed',
      'label' => 'Confirmed',
      'badge' => 'badge-success',
      'select_class' => 'bg-success',
    ],
    'closed' => [
      'title' => 'Closed Requests',
      'dropdown' => 'Closed',
      'label' => 'Closed',
      'badge' => 'badge-secondary',
      'select_class' => 'bg-secondary',
    ],
    'cancelled' => [
      'title' => 'Cancelled Requests',
      'dropdown' => 'Cancelled',
      'label' => 'Cancelled',
      'badge' => 'badge-danger',
      'select_class' => 'bg-danger',
    ],
  ],
  'editable_tour_statuses' => ['pending', 'confirmed'],

  'quote_requests' => [
    '' => [
      'title' => 'All Requests',
      'dropdown' => 'All',
      'label' => 'All',
      'badge' => '',
      'select_class' => '',
    ],
    'pending' => [
      'title' => 'Pending Requests',
      'dropdown' => 'Pending',
      'label' => 'Pending',
      'badge' => 'badge-warning',
      'select_class' => 'bg-warning text-dark',
    ],
    'responded' => [
      'title' => 'Responded Requests',
      'dropdown' => 'Responded',
      'label' => 'Responded',
      'badge' => 'badge-info',
      'select_class' => 'bg-success',
    ],
    'in_progress' => [
      'title' => 'In Progress Requests',
      'dropdown' => 'In Progress',
      'label' => 'In Progress',
      'badge' => 'badge-info',
      'select_class' => 'bg-info',
    ],
    'closed' => [
      'title' => 'Closed Requests',
      'dropdown' => 'Closed',
      'label' => 'Closed',
      'badge' => 'badge-secondary',
      'select_class' => 'bg-secondary',
    ],
    'cancelled' => [
      'title' => 'Cancelled Requests',
      'dropdown' => 'Cancelled',
      'label' => 'Cancelled',
      'badge' => 'badge-danger',
      'select_class' => 'bg-danger',
    ],
  ],
  'editable_quote_statuses' => ['pending', 'responded', 'in_progress'],

  'booking_records' => [
    '' => [
      'title' => 'All Bookings',
      'dropdown' => 'All',
      'label' => 'All',
      'badge' => '',
      'select_class' => '',
    ],
    'pending' => [
      'title' => 'Pending Bookings',
      'dropdown' => 'Pending',
      'label' => 'Pending',
      'badge' => 'badge-warning',
      'select_class' => 'bg-warning text-dark',
    ],
    'approved' => [
      'title' => 'Approved Bookings',
      'dropdown' => 'Approved',
      'label' => 'Approved',
      'badge' => 'badge-success',
      'select_class' => 'bg-success',
    ],
    'rejected' => [
      'title' => 'Rejected Bookings',
      'dropdown' => 'Rejected',
      'label' => 'Rejected',
      'badge' => 'badge-danger',
      'select_class' => 'bg-danger',
    ],
  ],

  'payment_statuses' => [
    '' => [
      'dropdown' => 'All',
      'label' => 'All',
      'badge' => '',
      'select_class' => '',
    ],
    'completed' => [
      'dropdown' => 'Completed',
      'label' => 'Completed',
      'badge' => 'badge-success',
      'select_class' => 'bg-success',
    ],
    'pending' => [
      'dropdown' => 'Pending',
      'label' => 'Pending',
      'badge' => 'badge-warning',
      'select_class' => 'bg-warning text-dark',
    ],
    'rejected' => [
      'dropdown' => 'Rejected',
      'label' => 'Rejected',
      'badge' => 'badge-danger',
      'select_class' => 'bg-danger',
    ],
  ],

  'editable_booking_statuses' => ['pending'],
  'editable_payment_statuses' => ['pending'],
];
