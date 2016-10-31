'use strict';

var VqQuizServices = angular.module('VqQuizServices', ['VqService']);

VqQuizServices.factory('vqQuizService', ['$http', 'vqRequest', 'vqApiBaseUrlV1',
    function ($http, vqRequest, vqApiBaseUrlV1) {
        var currentQuizId;

        return {
            createQuiz: createQuiz,
            getCurrentQuizId: getCurrentQuizId,
            setCurrentQuizId: setCurrentQuizId,
            getQuizResult: getQuizResult
        };

        // -------------------------------------------------------------------------------------------------------------
        // Public methods
        // -------------------------------------------------------------------------------------------------------------

        function createQuiz() {
            currentQuizId = null;

            return $http({
                method: 'POST',
                url: vqApiBaseUrlV1 + '/quiz',
                data: {
                    'create_quiz': {
                        'type': 'en'
                    }
                }
            }).then(vqRequest.handleSuccess, vqRequest.getErrorHandler());
        }

        function getCurrentQuizId() {
            return currentQuizId;
        }

        function setCurrentQuizId(quizId) {
            currentQuizId = quizId;
        }

        function getQuizResult(quizId) {
            return $http({
                method: 'GET',
                url: vqApiBaseUrlV1 + '/quiz/' + quizId
            }).then(vqRequest.handleSuccess, vqRequest.getErrorHandler());
        }
    }
]);

VqQuizServices.factory('vqQuizCurrentQuestion', ['$http', 'vqRequest', 'vqApiBaseUrlV1',
    function ($http, vqRequest, vqApiBaseUrlV1) {
        var possibleAnswers = [];
        var question;

        return {
            setupCurrentQuestion: setupCurrentQuestion,
            getQuestion: getQuestion,
            getPossibleAnswers: getPossibleAnswers,
            answer: answer
        };

        // -------------------------------------------------------------------------------------------------------------
        // Public methods
        // -------------------------------------------------------------------------------------------------------------

        function setupCurrentQuestion($quizId) {
            return $http({
                method: 'GET',
                url: vqApiBaseUrlV1 + '/quiz/' + $quizId + '/current-question'
            })
                .then(vqRequest.handleSuccess, vqRequest.getErrorHandler())
                .then(function (response) {
                    setupInternalData(response);
                });
        }

        function getQuestion() {
            return question.word;
        }

        function getPossibleAnswers() {
            return possibleAnswers;
        }

        function answer(quizId, answerId) {
            return $http({
                method: 'POST',
                url: vqApiBaseUrlV1 + '/quiz/' + quizId + '/answer',
                data: {
                    answer_quiz: {
                        answer_id: answerId
                    }
                }
            })
                .then(vqRequest.handleSuccess, vqRequest.getErrorHandler())
        }

        // -------------------------------------------------------------------------------------------------------------
        // Private methods
        // -------------------------------------------------------------------------------------------------------------

        function setupInternalData(response) {
            possibleAnswers = response.possible_answers;
            question = response.question;
        }
    }
]);