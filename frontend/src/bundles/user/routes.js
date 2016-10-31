'use strict';

VqRouteModule.config(['$stateProvider', function ($stateProvider) {
        $stateProvider
            .state('login', {
                url: '/',
                views: {
                    content: {
                        templateUrl: 'src/bundles/user/templates/login.html',
                        controller: 'vqLoginController',
                        data: {
                            requiresNonAuthentication: true
                        }
                    }
                }
            })
    }]
);