/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


teacherApp.controller('teacher', ['$scope', '$http', function ($scope, $http) {
        
        $scope.lessons      = [],
        $scope.draftLessons = [],
        $scope.teacher      = [],
        $scope.teacherMeta  = [],
        $scope._l           = _l,
        
        //Get Today Date!
        $scope.getTodayDate = function() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            
            var yyyy = today.getFullYear();
            if(dd < 10){
                dd = '0' + dd;
            }
            if(mm < 10){
                mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;
            return today;
        },
        //
        $scope.remaining = function ( $date ){
            
            if( $date ){
                var curr   = $scope.getTodayDate();
                var curr_y = parseInt(curr.split('-')[0]);
                var curr_m = parseInt(curr.split('-')[1]);
                var curr_d = parseInt(curr.split('-')[2]);
                
                var y = parseInt($date.split('-')[0]);
                var m = parseInt($date.split('-')[1]);
                var d = parseInt($date.split('-')[2]);
                
                var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
                var firstDate  = new Date(y, m, d);
                var secondDate = new Date(curr_y, curr_m, curr_d);
                
                var diffDays = Math.round(((firstDate.getTime() - secondDate.getTime()) / (oneDay)));
                if( diffDays > 0 ){
                    if( diffDays < 10 ){
                        return '0' + diffDays;
                    }
                    return diffDays;
                }
            }
            return '00';
        },
        //
        $scope.loadLessons = function() {
            $http.get('teacher/getlessons')
                    .success(function(data){
                        if(data.status === 'pass'){
                            $scope.lessons = data.lessons;
                        } else {
                            alert('LESSONS ERROR');
                        }
                    })
                    .error(function(){
                        alert('LESSONS ERROR')
                    });
        };
        $scope.loadDraftLessons = function() {
            $http.get('teacher/getdraftlessons')
                    .success(function(data){
                        if(data.status === 'pass'){
                            $scope.draftLessons = data.draftLessons;
                        } else {
                            alert('draft Lessons ERROR');
                        }
                    })
                    .error(function(){
                        alert('draft Lessons ERROR')
                    });
        };
        $scope.loadTeacher = function() {
            $http.get('teacher/getteacher')
                    .success(function(data){
                        if(data.status === 'pass'){
                            $scope.teacher = data.teacher;
                        } else {
                            alert('teacher ERROR');
                        }
                    })
                    .error(function(){
                        alert('teacher ERROR')
                    });
            //Get Meta Info
            $http.get('teacher/getteachermeta')
                    .success(function(data){
                        if(data.status === 'pass'){
                            $scope.teacherMeta = data.teacherMeta;
                        } else {
                            alert('teacher Meta ERROR');
                        }
                    })
                    .error(function(){
                        alert('teacher Meta ERROR')
                    });
        };
        
}]);