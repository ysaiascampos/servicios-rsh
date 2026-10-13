*** Settings ***
Library           SeleniumLibrary
Resource          ResourceM_COM.robot

*** Variables ***

*** Test Cases ***
TC06_RSH_DescargaAntecedentes
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
    click    xpath=(//button[@type='button'])[2]
    Sleep    5s
    click    xpath=//*[@id="id_tipo_solicitud"]
    click    //*[@id="id_tipo_solicitud"]/option[16]
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[6]
    Sleep    3s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    sleep    2s
    scrollView    xpath=//*[@id="mando"]/div[3]/div/div/div[1]/div/div[2]
    sleep    2s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    sleep    2s
    scrollView    xpath=//*[@id="wrapper"]/footer/div
    sleep    4s
    click    xpath=//*[@id="dropdownOpciones"]/button
    sleep    3s
    click    //*[@id="sol_15185837"]/li[5]/a
    #---------------------------------------------------------------
    Sleep    4s
    close
    [Teardown]    Close Browser
