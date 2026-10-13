*** Settings ***
Library           SeleniumLibrary
Resource          ResourceREC.robot

*** Variables ***

*** Test Cases ***
TC01_RSH_REC_RectificaPropiedadAutomovil
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
    type    xpath=//*[@id="rut_usuario"]    ${RUTCIUDADANO}
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    ${DVCIUDADANO}
    captureEntirePageScreenshot
    click    xpath=//*[@id="btn_consultar"]
    #---------------------------------------------------------------
    Sleep    4s
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[2]/div/div[2]/div/div/h1    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="ir_tra_op4"]
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    2s
    click    xpath=//*[@id="content"]/form[2]/div/div/div/div[6]/div/button
    Sleep    4s
    #---------------------------------------------------------------
    #---Seleccion Integrante a Rectificar
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="nombreGlosa"]/h3    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="11776630-6"]
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton continuar
    click    xpath=//*[@id="btnfin"]
    Sleep    6s
    #---------------------------------------------------------------
    #---Seleccion Tipo Solicitud
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[4]/div/h3    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="ir_rec_op6"]
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton continuar
    click    xpath=//*[@id="content"]/form[2]/div[2]/div/button[2]
    #---------------------------------------------------------------
    #---Confirma datos de Solicitante
    Sleep    4s
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="encp1"]/div[1]    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton continuar
    click    xpath=//*[@id="content"]/div[4]/div/form/div[2]/div/button[2]
    #---------------------------------------------------------------
    #---Seleccion Automovil
    Sleep    4s
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[4]/div/div[1]/div/div[1]    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton para editar automovil
    click    xpath=//html/body/div/div/section/div[4]/div/div[1]/div/div[3]/div/table/tbody/tr[4]/td[4]/div/button
    #---------------------------------------------------------------
    #---Pantalla Rectificacion de Propiedad y Motivo
    Sleep    10s
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[3]/div/h1    PAGE_DOWN
    Sleep    1s
    #---Motivo
    click    xpath=//*[@id="cmbMot"]
    click    xpath=//*[@id="cmbMot"]/option[contains(text(),"Error en registro de modelo y/o versión del vehículo")]
    Sleep    1s
    #---Modelo
    click    xpath=//*[@id="txtModelo"]
    Sleep    5s
    click    xpath=//*[@id="txtModelo"]/option[contains(text(),"SAIL15A")]
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="btnContinuar"]
    #---------------------------------------------------------------
    #---Regreso a pantalla anterior
    Sleep    4s
    captureEntirePageScreenshot
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    1s
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
