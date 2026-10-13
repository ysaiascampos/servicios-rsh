*** Settings ***
Library           SeleniumLibrary
Resource          ResourceCDV.robot

*** Variables ***

*** Test Cases ***
TC05_RSH_CDV_EliminaDigitacionDomicilio
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
    #---------------------------------------------------------------
    Sleep    6s
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${RUTCIUDADANO}
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    click    xpath=//*[@id="id_tipo_solicitud"]/option[contains(text(),"Cambio de Domicilio y Vivienda")]
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[contains(text(),"En Digitacion")]
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    6s
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="dropdownOpciones"]/button
    Sleep    1s
    captureEntirePageScreenshot
    click    link=Rechazar Solicitud
    Sleep    3s
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
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    4s
    captureEntirePageScreenshot
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
