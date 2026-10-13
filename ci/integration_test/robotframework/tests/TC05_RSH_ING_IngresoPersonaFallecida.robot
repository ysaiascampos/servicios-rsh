*** Settings ***
Library           SeleniumLibrary
Resource          ResourceING.robot

*** Variables ***

*** Test Cases ***
TC05_RSH_ING_IngresoPersonaFallecida
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
    type    xpath=//*[@id="rut_usuario"]    3225250
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    8
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="btn_consultar"]
    Sleep    4s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
