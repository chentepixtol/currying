Example:

    require_once 'Currying.php';

    $calc = new Currying(function ($operation, $operator1, $operator2){
        if( '+' == $operation ){
            return $operator1 + $operator2;
        }else if( '-' == $operation ){
            return $operator1 - $operator2;
        }else if( '*' == $operation ){
            return $operator1 * $operator2;
        }
    });

    $add = $calc('+');
    $sub = $calc('-');
    $multiply = $calc('*');

    echo $add(20, 10); // 30
    echo $sub(20, 10); // 10
    echo $multiply(20, 10); // 200
