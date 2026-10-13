*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceM_INT.robot

*** Variables ***

*** Test Cases ***
TC12_RSH_LOG_visualizarsolicitud
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
    #-------------------------------------------------------------------
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/a
    #scrollView    xpath=//*[@id="mando"]/div[2]
    sleep    2s
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/ul/li[1]/a
    Sleep    1s
    click    xpath=//*[@id="rut_usuario"]
    type    xpath=//*[@id="rut_usuario"]    ${USER2}
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    ${dv}
    sleep    4s
    click    xpath=//*[@id="btn_consultar"]
    sleep    4s
    click    xpath=//*[@id="actualizaForm"]
    sleep    4s
    click    xpath=//button[text()="Continuar"]
    sleep    5s
    click    xpath=//*[@id="chkIncorpora"]
    sleep    10s
    #Scroll Page To Location    0    $("#ulactuv").offset().top-50
    click    xpath=//button[text()="Continuar"]
    sleep    10s
    Scroll Page To Location    0    $("#btnContinuar").offset().top-50
    sleep    5s
    click    xpath=//button[text()="Siguiente"]
    sleep    10s
    Scroll Page To Location    0    $("#txtRun").offset().top-50
    sleep    10s
    click    xpath=//*[@id="txtRun"]
    type    xpath=//*[@id="txtRun"]    ${USERinc}
    Sleep    1s
    click    xpath=//*[@id="txtDV"]
    type    xpath=//*[@id="txtDV"]    ${dvinc}
    sleep    6s
    click    xpath=//*[@id="txtResNombres"]
    type    xpath=//*[@id="txtResNombres"]    ${nominc}
    sleep    6s
    click    xpath=//*[@id="1"]/button/span
    sleep    15s
    click    xpath=//*[@id="content"]/div[4]/div/button[2]
    sleep    10s
    #click    xpath=//button[text()="Continuar"]
    sleep    6s
    click    xpath=//*[@id="int1"]/div/div[2]/div[1]/div/button
    sleep    10s
    click    xpath=//*[@id="int1"]/div/div[2]/div[2]/button[2]
    click    link=Cerrar sesión
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
