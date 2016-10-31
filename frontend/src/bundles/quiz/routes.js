'use strict';

VqRouteModule.config(['$stateProvider', function ($stateProvider) {
        $stateProvider
            .state('quiz', {
                url: '/quiz',
                views: {
                    content: {
                        templateUrl: 'src/bundles/quiz/templates/question.html',
                        controller: 'vqQuestionController',
                        data: {
                            requiresAuthentication: true
                        }
                    }
                }
            })
            .state('result', {
                url: '/quiz/result',
                views: {
                    content: {
                        templateUrl: 'src/bundles/quiz/templates/result.html',
                        controller: 'vqResultController'
                    }
                }
            })
    }]
);