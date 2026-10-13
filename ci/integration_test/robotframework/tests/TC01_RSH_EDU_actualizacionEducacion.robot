*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceM_EDU.robot

*** Variables ***

*** Test Cases ***
TC01_RSH_actualizacionEducacion
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
    click    xpath=//*[@id="chkEducacion"]
    sleep    10s
    click    xpath=//button[text()="Continuar"]
    sleep    10s
    Scroll Page To Location    0    $("#btnContinuar").offset().top-50
    sleep    5s
    click    xpath=//button[text()="Siguiente"]
    sleep    10s
    click    xpath=//html/body/div/div/section/div[4]/div/div/div[2]/div[1]/table/tbody/tr[1]/td[1]/input
    ${USERinc}=    Get Text    //*[@id="dataTables-example"]/tbody/tr[1]/td[2]
    sleep    6s
    click    //*[@id="${USERinc}_e2"]
    click    //*[@id="${USERinc}_e24"]
    click    xpath=//*[@id="${USERinc}_e4"]
    click    //*[@id="${USERinc}_e410"]
    click    xpath=//*[@id="${USERinc}_e3"]
    click    xpath=//*[@id="${USERinc}_e322"]
    sleep    6s
    click    xpath=//html/body/div/div/section/div[4]/div/button[2]
    Mouse Over    xpath=//*[@id="btnfin"]
    sleep    10s
    click    link=Cerrar sesión
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
