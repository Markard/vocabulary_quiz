"use strict";

var VqService = angular.module('VqService', ['VqUserServices']);

/**
 * Request service. Use it for http request.
 */
VqService.factory('vqRequest', [
    '$q',
    function ($q) {
        return {
            handleSuccess: handleSuccess,
            getErrorHandler: getErrorHandler
        };

        // -------------------------------------------------------------------------------------------------------------
        // Private methods
        // -------------------------------------------------------------------------------------------------------------

        function handleSuccess(response) {
            return response.data;
        }

        /**
         * @return {errorHandler}
         */
        function getErrorHandler() {
            return errorHandler;

            function errorHandler(response) {
                if (!angular.isObject(response.data) || response.data.status === RESPONSE_ERROR) {
                    return $q.reject();
                } else {
                    return $q.reject(response.data);
                }
            }
        }
    }
]);

/**
 * Handle authentication.
 */
VqService.factory('vqSecurityFirewall', [
    '$state',
    'vqCurrentUser',
    function ($state, vqCurrentUser) {
        return {
            protect: protect
        };

        // ---------------------------------------------------------------------------------------------------------
        // Public methods
        // ---------------------------------------------------------------------------------------------------------

        function protect(stateData, event) {
            var currentUserStatus = vqCurrentUser.getStatus();

            if (stateData.requiresLogin && currentUserStatus === USER_STATUS_NON_AUTHENTICATED) {
                event.preventDefault();
                $state.go('login');
            }
        }
    }
]);

/**
 * Define common url format for requests.
 */
VqService.factory('vqApiBaseUrlV1', [function () {
    return 'http://127.0.0.1:8001/api';
}]);