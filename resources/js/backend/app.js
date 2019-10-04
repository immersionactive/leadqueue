import '@coreui/coreui'

/**
 * Activate Bootstrap popovers.
 * We limit the selector to specific element types for performance reasons -
 * that is, so jQuery doesn't have to inspect every single node in the DOM.
 */

$('i[data-toggle=popover]').popover();
