*** Settings ***
Library           SeleniumLibrary
Resource          ResourceGES.robot

*** Variables ***

*** Test Cases ***
TC11_RSH_GES_RechazarSolicitudPendienteEjecutor
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
    scrollView    xpath=//*[@id="mando"]/div[2]
    sleep    2s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${SOLICITUD2}
    Sleep    2s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    sleep    2s
    scrollView    xpath=//*[@id="mando"]/div[3]/div/div/div[1]/div/div[1]
    sleep    2s
    click    xpath=//*[@id="id_tipo_solicitud"]
    sleep    2s
    click    xpath=//*[@id="id_tipo_solicitud"]/option[2]
    sleep    2s
    click    xpath=//*[@id="id_estado"]
    sleep    2s
    click    xpath=//*[@id="id_estado"]/option[3]
    sleep    2s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    sleep    2s
    scrollView    xpath=//*[@id="wrapper"]/footer/div
    sleep    4s
    click    xpath=//*[@id="dropdownOpciones"]/button
    sleep    3s
    click    xpath=//*[@id="sol_15185338"]/li[7]/a
    sleep    4s
    click    xpath=//*[@id="frmAsignar1"]/div[2]/div/div[2]/input[2]
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
