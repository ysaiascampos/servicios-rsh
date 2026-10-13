*** Settings ***
Library           SeleniumLibrary
Resource          ResourceCDV.robot

*** Variables ***

*** Test Cases ***
TC01_RSH_CDV_VisualizaDomicilio
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
    click    xpath=//*[@id="content2"]/div/div[1]/div/button
    Sleep    2s
    captureEntirePageScreenshot
    Sleep    1s
    click    xpath=//html/body/div[1]/div/section/section/div/div[5]/div/div/div[1]/button/span
    Sleep    2s
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    4s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
