/**
 * Form in pop-up dialog
 */
CRM.$(function ($) {
    'use strict';
    $('.webhook-action').on('crmPopupFormSuccess', CRM.refreshParent);
});
