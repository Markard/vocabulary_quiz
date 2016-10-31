'use strict';

var VqUserControllers = angular.module('VqUserControllers', ['VqService', 'VqUserServices']);

VqUserControllers.controller('vqLoginController', [
        '$scope',
        '$state',
        'store',
        'vqUserService',
        'vqCurrentUser',
        function ($scope, $state, store, vqUserService, vqCurrentUser) {

            $scope.username = vqCurrentUser.getUsername();

            // ---------------------------------------------------------------------------------------------------------
            // Public methods
            // ---------------------------------------------------------------------------------------------------------

            $scope.login = function (username) {
                vqUserService
                    .login(username)
                    .then(function (response) {
                        store.set('jwt', response.token);

                        vqCurrentUser.fillUserDataWithToken(response.token);
                        vqCurrentUser.setStatus(USER_STATUS_AUTHENTICATED);

                        $state.go('quiz');
                    });
            };
        }
    ]
);