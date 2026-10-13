*** Settings ***
Library           SeleniumLibrary
Resource          ResourceING.robot

*** Variables ***

*** Test Cases ***
TC04_RSH_ING_IngresoRutEmpresa
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    # open    ${URL}
    Maximize Browser Window
    click    xpath=//*[@id="login-username"]
    type    xpath=//*[@id="login-username"]    ${USER}
    Sleep    1s
    click    xpath=//*[@id="login-password"]
    type    xpath=//*[@id="login-password"]    ${PASSWORD}
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="loginform"]/div[3]/div/button
    Sleep    7s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/a
    Mouse Over    //a[contains(text(),"Ingresar Solicitudes")]
    captureEntirePageScreenshot
    click    //a[contains(text(),"Ingresar Solicitudes")]
    Sleep    3s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    xpath=//*[@id="rut_usuario"]
    type    xpath=//*[@id="rut_usuario"]    89807200
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    2
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="btn_consultar"]
    Sleep    4s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    #---Ingreso de datos:
    #---Nombres
    click    xpath=//*[@id="nuevo-nombres"]
    type    xpath=//*[@id="nuevo-nombres"]    Farmacia
    Sleep    1s
    #---Primer Apellido
    click    xpath=//*[@id="nuevo-apat"]
    type    xpath=//*[@id="nuevo-apat"]    Cruz
    Sleep    1s
    #---Segundo Apellido
    click    xpath=//*[@id="nuevo-amat"]
    type    xpath=//*[@id="nuevo-amat"]    Verde
    Sleep    1s
    #---Fecha nacimiento
    click    xpath=//*[@id="nuevo-fechanacimiento"]
    type    xpath=//*[@id="nuevo-fechanacimiento"]    01/01/1980
    Sleep    1s
    #---Sexo
    click    xpath=//*[@id="nuevo-sexo"]
    click    //option[contains(text(),"Femenino")]
    Sleep    1s
    #---Estado civil
    click    xpath=//*[@id="nuevo-estadocivil"]
    click    //option[contains(text(),"SOLTERO")]
    Sleep    1s
    #---Telefono
    click    xpath=//*[@id="nuevo-telefono"]
    type    xpath=//*[@id="nuevo-telefono"]    21234567
    Sleep    1s
    #---Celular
    click    xpath=//*[@id="nuevo-celular"]
    type    xpath=//*[@id="nuevo-celular"]    99999999
    Sleep    1s
    #---Mail
    click    xpath=//*[@id="nuevo-email"]
    type    xpath=//*[@id="nuevo-email"]    mail@mail.com
    Sleep    1s
    #---
    captureEntirePageScreenshot
    Mouse Over    //*[@id="modalnuevapersona"]/div/div/div[4]/button[1]
    #click    //*[@id="modalnuevapersona"]/div/div/div[4]/button[1]
    Sleep    1s
    click    //*[@id="modalnuevapersona"]/div/div/div[4]/button[2]
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
