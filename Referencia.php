<?php
    /**
    *  Generar Referencia Bancaria para referenciado
    *  deacuerdo a la matricula de alumno de la
    *
    *  @param   Int
    *  @param   String
    *  @param   Int
    *  @param   Float
    *  @param   String
    *
    *  @return  String
    *
    */
    function Referencia( $id_recibo, $paquete_id, $matricula, $monto, $fecha_limite ){

        //CALCULO DE LA FECHA CONDENSADA
        $fechalimite = explode("-",$fecha_limite);
        //Calculamos Año
        $fecha['anio']  = ( ((int)$fechalimite[0]-2009)*372 );
        //Calculamos en Mes
        $fecha['mes']  = ( ((int)$fechalimite[1]-1)*31 );
        //Calculamos dís
        $fecha['dia']  = ((int)$fechalimite[2]-1);

        $suma_fecha     = ( $fecha['anio']+$fecha['mes']+$fecha['dia'] );
        $fecha_condensada    = substr($suma_fecha,0,4);
        //Fin cálculo

        //CALCULO DEL MONTO CONDENSADO

        $x = (float)$monto; 
        $x_entero = (int)$monto; 

        if($x - $x_entero){ 

            $decimales = explode(".",$monto);

            if(strlen($decimales[1])==1){

                $decimal = $decimales[1].'0';

                $monto = $decimales[0].$decimal;
            }else{

                $monto = $decimales[0].$decimales[1];
            }
        } 
        else{ 

            $monto = $monto."00";
        }

        $data_num[1] = 7;
        $data_num[2] = 3;
        $data_num[3] = 1;
        $index = 1;

        /*for ($i=0; $i < strlen($monto) ; $i++) { 
            echo $monto[$i];
        }*/

        for ($i=strlen($monto)-1; $i >=0 ; $i--) { 

         $resultado = $resultado + ( $monto[$i]*$data_num[$index]);
         $index++;

         if($index==4){
            $index=1;
        }
    }

    $importe_condensado = $resultado%10;

    $referencia_integrada = $id.$paquete_id.$matricula.$fecha_condensada.$importe_condensado.'2';

    $data_n[1] = 11;
    $data_n[2] = 13;
    $data_n[3] = 17;
    $data_n[4] = 19;
    $data_n[5] = 23;
    $index = 1;
    $resultado = 0;

    for ($i=strlen($referencia_integrada)-1; $i >=0 ; $i--) { 
        
        $resultado = $resultado + ( $referencia_integrada[$i]*$data_n[$index]);
        $index++;

        if($index==6){
            $index=1;
        }
    }

    $digito_verificador = ($resultado%97)+1;

    if( strlen($digito_verificador)==1){
        $digito_verificador = '0'.$digito_verificador;
    }

    $referencia = $referencia_integrada.$digito_verificador;

    return $referencia;
}