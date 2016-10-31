'use strict';

var VqConfig = angular.module('VqConfig', [
    'ui.router',
    'angular-jwt',
    'angular-storage'
]);

VqConfig.config(['$httpProvider', 'jwtOptionsProvider',
    function ($httpProvider, jwtOptionsProvider) {
        jwtOptionsProvider.config({
            whiteListedDomains: ['localhost', '127.0.0.1'],
            tokenGetter: ['store', function (store) {
                return store.get('jwt');
            }]
        });

        $httpProvider.interceptors.push('jwtInterceptor');
        $httpProvider.interceptors.push(['$q', '$injector', function ($q, $injector) {
            return {
                'responseError': function (response) {
                    if (response.status === 401 || response.status === 403) {
                        $injector.get('$state').transitionTo('login');
                    }
                    return $q.reject(response);
                }
            };
        }]);
    }]);

VqConfig.run(['$rootScope', 'vqSecurityFirewall', function ($rootScope, vqSecurityFirewall) {
    // Enable authentication.
    $rootScope.$on('$stateChangeStart', function (e, to) {
        if (to.views.content.data) {
            vqSecurityFirewall.protect(to.views.content.data, e)
        }
    });
}]);