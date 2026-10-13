*** Settings ***
Library           SeleniumLibrary
Resource          ResourceREC.robot

*** Variables ***

*** Test Cases ***
TC05_RSH_REC_RechazaRectificacionIngresos
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    # open    ${URL}
    Maximize Browser Window
    Sleep    2s
    captureEntirePageScreenshot
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
    #---Busca el Registro para Rechazarlo
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${RUTCIUDADANO2}
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    click    xpath=//*[@id="id_tipo_solicitud"]/option[contains(text(),"Rectificación Ingreso Dependiente e Independiente")]
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[contains(text(),"Pendiente Entregar Documentos")]
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
    captureEntirePageScreenshot
    Sleep    3s
    click    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div/textarea
    type    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[1]/div/textarea    PRUEBA ROBOTFRAMEWORK
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//html/body/div[1]/div[2]/div/div/div[2]/form/div[2]/div/div[2]/button
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    1s
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
