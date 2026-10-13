*** Settings ***
Library           SeleniumLibrary
Resource          ResourceM_EDU.robot

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
    click    id=id_tipo_solicitud
    Sleep    2s
    click    //option[contains(text(),"Actualización de Módulo Educación")]
    click    id=id_estado
    Sleep    2s
    click    //option[contains(text(),"Pendiente Entregar Documentos")]
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
    click    //*[@id="sol_15185531"]/li[5]/a
    #---------------------------------------------------------------
    Sleep    4s
    close
    [Teardown]    Close Browser
