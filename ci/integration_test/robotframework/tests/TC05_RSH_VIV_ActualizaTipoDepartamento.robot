*** Settings ***
Library           SeleniumLibrary
Resource          ResourceVIV.robot

*** Variables ***

*** Test Cases ***
TC05_RSH_VIV_ActualizaTipoDepartamento
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
    Sleep    2s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/a
    Mouse Over    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/ul/li[2]/a
    Sleep    2s
    captureEntirePageScreenshot
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/ul/li[2]/a
    Sleep    3s
    click    xpath=//*[@id="rut_usuario"]
    type    xpath=//*[@id="rut_usuario"]    ${RUTCIUDADANO5}
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    ${DVCIUDADANO5}
    captureEntirePageScreenshot
    click    xpath=//*[@id="btn_consultar"]
    #---------------------------------------------------------------
    Sleep    4s
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[2]/div/div[2]/div/div/h1    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="ir_tra_op2"]
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    2s
    click    xpath=//*[@id="content"]/form[2]/div/div/div/div[6]/div/button
    Sleep    4s
    #---------------------------------------------------------------
    #---Solicitud de actualización Vivienda
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[2]/div/div[2]/div/div/h1    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="chkVivienda"]
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton continuar
    click    xpath=//*[@id="content"]/form[2]/div[2]/div/button
    Sleep    6s
    #---------------------------------------------------------------
    #---Pantalla de Datos de contacto del solicitante
    sendKeys    xpath=//*[@id="content"]/div[4]/div/h1    PAGE_DOWN
    captureEntirePageScreenshot
    #---Boton continuar
    click    xpath=//*[@id="btnContinuar"]
    captureEntirePageScreenshot
    Sleep    6s
    #---------------------------------------------------------------
    #---Pantalla Situación Habitacional registrada
    sendKeys    xpath=//*[@id="content"]/div[3]/div/div[1]/div[1]    PAGE_DOWN
    captureEntirePageScreenshot
    Sleep    1s
    sendKeys    xpath=//*[@id="content"]/div[3]/div/div[2]/div[1]    PAGE_DOWN
    captureEntirePageScreenshot
    Sleep    2s
    #---v1
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[1]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[1]/div/select/option[contains(text(),"2. Departamento")]
    Sleep    1s
    #---v2
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[2]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[2]/div/select/option[contains(text(),"1. Propio pagado o pagándose")]
    Sleep    1s
    #---v3
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[3]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[3]/div/select/option[contains(text(),"1. Propia pagado o pagándose")]
    Sleep    1s
    #---v4
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[4]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[4]/div/select/option[contains(text(),"1. Red pública con medidor propio")]
    Sleep    1s
    #---v5
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[5]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[5]/div/select/option[contains(text(),"1. Con llave dentro de la vivienda")]
    Sleep    1s
    #---v6
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[6]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[6]/div/select/option[contains(text(),"Conectado a alcantarillado")]
    Sleep    1s
    #---v7
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[7]/div/input
    Sleep    1s
    type    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[7]/div/input    3
    Sleep    1s
    #---v8
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[8]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[8]/div/select/option[contains(text(),"1. Sí")]
    Sleep    1s
    #---v9
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[9]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[9]/div/select/option[contains(text(),"1. Hormigón armado")]
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[9]/div[2]/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[9]/div[2]/select/option[contains(text(),"1. Bueno")]
    #---v10
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[10]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[10]/div/select/option[contains(text(),"2. Cerámico, flexit o similar")]
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[10]/div[2]/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[10]/div[2]/select/option[contains(text(),"1. Bueno")]
    #---v11
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[11]/div/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[11]/div/select/option[contains(text(),"2. Losa hormigón")]
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[11]/div[2]/select
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[3]/div/div[2]/div[2]/form/div[11]/div[2]/select/option[contains(text(),"1. Bueno")]
    captureEntirePageScreenshot
    Sleep    1s
    #---Boton Continuar
    click    xpath=//*[@id="frmV"]/div[12]/div/button[2]
    Sleep    4s
    #---Ventana popup
    click    xpath=//html/body/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div[1]/input
    Sleep    1s
    click    xpath=//html/body/div[4]/div[1]/table/tbody/tr[2]/td[2]
    Sleep    1s
    click    xpath=//html/body/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div[2]/input
    Sleep    1s
    click    xpath=//html/body/div[4]/div[1]/table/tbody/tr[5]/td[6]
    Sleep    1s
    click    xpath=//html/body/div[2]/div/div/div[2]/form/div[2]/div/div[2]/div/textarea
    type    xpath=//html/body/div[2]/div/div/div[2]/form/div[2]/div/div[2]/div/textarea    PRUEBA ROBOTFRAMEWORK
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//html/body/div[2]/div/div/div[2]/form/div[2]/div/div[3]/button
    Sleep    3s
    #---------------------------------------------------------------
    captureEntirePageScreenshot
    #---boton descargar formulario
    #click    xpath=//html/body/div[1]/div/section/div[2]/div/div/div/div[2]/div[1]/button
    #---
    #---boton pantalla anterior
    click    xpath=//html/body/div[1]/div/section/div[2]/div/div/div/div[2]/div[2]/button[1]
    captureEntirePageScreenshot
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    #======= ACA ELIMINA SOLICITUD PARA DEJAR RUT DISPONIBLE =======
    #---------------------------------------------------------------
    click    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[1]/div/div[1]/input
    type    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[1]/div/div[1]/input    ${RUTCIUDADANO5}
    Sleep    1s
    click    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[2]/div/div/div[1]/select
    Sleep    1s
    click    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[2]/div/div/div[1]/select/option[contains(text(),"Actualización de módulo Vivienda")]
    Sleep    1s
    click    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[2]/div/div/div[2]/select
    Sleep    1s
    click    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[2]/div/div/div[2]/select/option[contains(text(),"Agendado")]
    captureEntirePageScreenshot
    click    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    10s
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="dropdownOpciones"]/button
    Sleep    1s
    captureEntirePageScreenshot
    click    link=Rechazar Solicitud
    Sleep    1s
    captureEntirePageScreenshot
    Sleep    3s
    click    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div/textarea
    type    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div/textarea    PRUEBA ROBOTFRAMEWORK
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[2]/button
    Sleep    3s
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
