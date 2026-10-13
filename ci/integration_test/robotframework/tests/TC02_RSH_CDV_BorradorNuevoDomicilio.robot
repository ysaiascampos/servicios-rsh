*** Settings ***
Library           SeleniumLibrary
Resource          ResourceCDV.robot

*** Variables ***

*** Test Cases ***
TC02_RSH_CDV_BorradorNuevoDomicilio
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
    click    xpath=//*[@id="ir_tra_op2"]
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    2s
    click    xpath=//*[@id="content"]/form[2]/div/div/div/div[6]/div/button
    Sleep    4s
    #---------------------------------------------------------------
    #---Actualización de domicilio e información de vivienda
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[2]/div/div[2]/div/div/h1    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="chkDomicilio"]
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
    #---Pantalla: Cambio de Domicilio e Información de vivienda
    captureEntirePageScreenshot
    click    xpath=//*[@id="frmCamDomCiudad"]
    click    xpath=//*[@id="frmCamDomCiudad"]/option[contains(text(),"SANTIAGO (CD)")]
    Sleep    1s
    click    xpath=//*[@id="frmCamDomAh"]
    click    xpath=//*[@id="frmCamDomAh"]/option[contains(text(),"FRANKLIN -OT")]
    Sleep    1s
    click    xpath=//*[@id="frmCamDomUv"]
    click    xpath=//*[@id="frmCamDomUv"]/option[contains(text(),"15")]
    Sleep    1s
    click    xpath=//*[@id="frmCamDomCalle"]
    click    xpath=//*[@id="frmCamDomCalle"]/option[contains(text(),"ALMIRANTE HURTADO")]
    Sleep    1s
    click    xpath=//*[@id="frmCamDomNumero"]
    type    xpath=//*[@id="frmCamDomNumero"]    123
    Sleep    1s
    click    xpath=//*[@id="frmCamDomReferencia"]
    type    xpath=//*[@id="frmCamDomReferencia"]    CERCANO AL PASAJE UNO
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton Borrador
    click    xpath=//*[@id="guardarBorrador"]
    Sleep    1s
    captureEntirePageScreenshot
    Sleep    6s
    #---------------------------------------------------------------
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    4s
    captureEntirePageScreenshot
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
