*** Settings ***
Library           SeleniumLibrary
Resource          ResourceING.robot

*** Variables ***

*** Test Cases ***
TC01_RSH_ING_SolicitudIngresoAlRegistro
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
    type    xpath=//*[@id="rut_usuario"]    13720653
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    6
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="btn_consultar"]
    Sleep    4s
    captureEntirePageScreenshot
    click    xpath=//*[@id="ir_tra_op1"]
    click    xpath=//button[text()="Continuar"]
    Sleep    4s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    sendKeys    xpath=//*[@id="encp1"]/div[1]    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//button[text()="Siguiente"]
    #---------------------------------------------------------------
    Sleep    4s
    captureEntirePageScreenshot
    scrollView    xpath=//*[@id="content"]/footer/div/div[3]    #PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    Sleep    4s
    scrollView    xpath=//*[@id="page-wrapper2"]/header
    click    xpath=//*[@id="formgrilla"]/div[1]/ul/li[2]/a
    Sleep    4s
    captureEntirePageScreenshot
    Sleep    1s
    sendKeys    //*[@id="content"]/div[2]/div/h1    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    #---Ingreso de datos:
    #---Comuna
    click    xpath=//*[@id="comuna"]
    click    //option[contains(text(),"SANTIAGO")]
    Sleep    1s
    #---Ciudad
    click    xpath=//*[@id="ciudad"]
    click    //option[contains(text(),"SANTIAGO (CD)")]
    #---Agrupacion habitacional
    click    xpath=//*[@id="agrupacionhabitacional"]
    click    //option[contains(text(),"MAPOCHO -OT")]
    Sleep    1s
    #---Unidad vecinal
    click    xpath=//*[@id="uv"]
    click    //option[contains(text(),"21")]
    Sleep    1s
    #---Calle
    click    xpath=//*[@id="calle"]
    Sleep    1s
    click    //option[contains(text(),"ALDUNATE")]
    Sleep    1s
    #---Numero
    click    xpath=//*[@id="numero"]
    type    xpath=//*[@id="numero"]    999
    Sleep    1s
    #---Referencia
    click    xpath=//*[@id="referencia"]
    type    xpath=//*[@id="referencia"]    A UNA CUADRA DE ANTENA CELULAR MOVISTAR
    Sleep    2s
    #---
    captureEntirePageScreenshot
    Mouse Over    xpath=//*[@id="formgrilla"]/div[2]/button[2]
    #click    xpath=//*[@id="formgrilla"]/div[2]/button[2]    #BOTON GENERAR DESHABILITADO
    Sleep    2s
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
