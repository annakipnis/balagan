/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


studentApp.controller('student', ['$scope', '$http', function ($scope, $http) {
        
        $scope.lessons    = [],
        $scope.homeworks  = [],
        $scope.dHomeworks = [],
        $scope._l         = _l,
        
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
        
        $http.get('student/getall')
                .success(function(data){
                    if(data.status === 'pass'){
                        $scope.lessons    = data.lessons;
                        $scope.homeworks  = data.homeworks;
                        $scope.dHomeworks = data.dHomeworks;
                    } else {
                        alert('Homeworks ERROR');
                    }
                })
                .error(function(){
                    alert('HomeWorks ERROR')
                });
        
}]);