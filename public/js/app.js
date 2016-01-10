/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * topXite
 * 
 */

var BalaganApp = angular.module('BalaganApp', []);
    BalaganApp.controller('Login', ['$scope', '$http', function ($scope, $http) {
            if( $('#login-form') ){
                $('#login-form').validate({ // initialize the plugin 
                    validClass: "success",
                    errorClass: "invalid",
                    rules: {
                        username: { required: true, email: true },
                        password: { required: true, minlength: 4 }
                    }
                });
            }
    }]);
    BalaganApp.controller('ManageStudents', ['$scope', '$http', function ($scope, $http) {
            if( $('#add-form') ){
                $('#add-form').validate({ // initialize the plugin 
                    validClass: "success",
                    errorClass: "invalid",
                    rules: {
                        studentName: { required: true, minlength: 2 },
                        birthDate: { required: true, minlength: 6 }
                    }
                });
            }
    }]);
    BalaganApp.controller('Games', ['$scope', '$http', function ($scope, $http) {
            
    }]);
    BalaganApp.controller('Goals', ['$scope', '$http', function ($scope, $http) {
            $scope.arrowUp = false;
            //
            $scope.activeArrowUp = function (){
                $scope.arrowUp = !$scope.arrowUp;
            };
    }]);
    BalaganApp.controller('Students', ['$scope', '$http', '$location', '$window', function ($scope, $http, $location, $window) {
            $scope.arrowUp    = false;
            $scope.student    = 0;
            $scope.grade      = 0;
            $scope.target     = 0;
            $scope.level      = 0;
            $scope.game       = 0;
            $scope.groupNotes = '';
            $scope.docMsg     = '';
            $scope.docStatus  = '';
            
            //
            $scope.activeArrowUp = function (){
                $scope.arrowUp = !$scope.arrowUp;
            };
            //
            $scope.saveParams = function ( studentID, groupID, gradeID, gameID ){
                $scope.student = studentID;
                $scope.group = groupID;
                $scope.grade   = gradeID;
                $scope.game    = gameID;
                $scope.sendGrade();
            };
            //
            $scope.saveNotes = function ( group_id, notes, url ){
                if( notes && notes.length ){
                    var task = {};
                    task.notes    = notes;
                    task.group_id = group_id;
                    var baseurl = document.getElementById('baseurl').value;
                    $scope.addToDB(task, baseurl+'/documentation/savenotes');
                    $scope.go(url, '/groups');
                }
            };
            $scope.go = function( url, path ){
                $window.location.href = url;
                $location.path(path);
            },
            //
            $scope.sendGrade = function (){
                var task = {};
                task.studentID = $scope.student;
                task.groupID = $scope.group;
                task.gradeID   = $scope.grade;
                task.gameID    = $scope.game;
                var baseurl = document.getElementById('baseurl').value;
                $scope.addToDB(task, baseurl+'/documentation/savegrade');
            };
            //
            $scope.addToDB = function( task, url ) {
                var params = $scope.params(task);
                $http({
                    url: url,
                    method: 'POST',
                    data: params
                }).success(function (data, status, headers, config) {
                    $scope.docMsg    = data.msg;
                    $scope.docStatus = data.status;
                    $scope.grade     = 0;
                    $('#student-answers-' + $scope.student).click();
                    $scope.activeArrowUp();
                    
                }).error(function (data, status, headers, config) {
                    $scope.docMsg = data.msg;
                    $scope.docStatus = data.status;
                });
            };
            //
            $scope.params = function(task) {
                $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
                var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

                for(name in task) {
                  value = task[name];

                  if(value instanceof Array) {
                    for(i=0; i<value.length; ++i) {
                      subValue = value[i];
                      fullSubName = name + '[' + i + ']';
                      innerObj = {};
                      innerObj[fullSubName] = subValue;
                      query += param(innerObj) + '&';
                    }
                  }
                  else if(value instanceof Object) {
                    for(subName in value) {
                      subValue = value[subName];
                      fullSubName = name + '[' + subName + ']';
                      innerObj = {};
                      innerObj[fullSubName] = subValue;
                      query += param(innerObj) + '&';
                    }
                  }
                  else if(value !== undefined && value !== null)
                    query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
                }

                return query.length ? query.substr(0, query.length - 1) : query;
            };
    }]);
