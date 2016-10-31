'use strict';

var VqUserServices = angular.module('VqUserServices', ['VqService']);

VqUserServices.factory('vqUserService', ['$http', 'vqRequest', 'vqApiBaseUrlV1',
    function ($http, vqRequest, vqApiBaseUrlV1) {
        return {
            login: login
        };

        // -------------------------------------------------------------------------------------------------------------
        // Public methods
        // -------------------------------------------------------------------------------------------------------------

        function login(username) {
            return $http({
                method: 'POST',
                url: vqApiBaseUrlV1 + '/user/registration',
                data: {
                    'registration': {
                        'username': username
                    }
                }
            }).then(vqRequest.handleSuccess, vqRequest.getErrorHandler());
        }
    }
]);

VqUserServices.factory('vqCurrentUser', ['jwtHelper', 'store', '$state',
    function (jwtHelper, store, $state) {
        var status = USER_STATUS_NON_AUTHENTICATED;
        var username;

        initialize();

        return {
            getStatus: getStatus,
            setStatus: setStatus,
            getUsername: getUsername,
            fillUserDataWithToken: fillUserDataWithToken
        };

        // -------------------------------------------------------------------------------------------------------------
        // Public methods
        // -------------------------------------------------------------------------------------------------------------

        function getStatus() {
            return status;
        }

        function setStatus(_status) {
            if (USER_STATUSES.indexOf(_status) === -1) {
                throw new Error('Invalid user status was set. You tried to set status: ' + _status);
            }

            status = _status;
        }

        function getUsername() {
            return username;
        }

        function fillUserDataWithToken(token) {
            var payload = jwtHelper.decodeToken(token);

            username = payload.username;
        }

        // -------------------------------------------------------------------------------------------------------------
        // Private methods
        // -------------------------------------------------------------------------------------------------------------

        function initialize() {
            var token = store.get('jwt');

            if (token && !jwtHelper.isTokenExpired(token)) {
                setStatus(USER_STATUS_AUTHENTICATED);
                fillUserDataWithToken(token);
            } else {
                setStatus(USER_STATUS_NON_AUTHENTICATED);
                $state.transitionTo('login');
            }
        }
    }
]);