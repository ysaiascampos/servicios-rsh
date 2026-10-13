*** Settings ***
Library           SeleniumLibrary
Resource          ResourceGES.robot

*** Variables ***

*** Test Cases ***
TC10_RSH_GES_AgendaVisitaSolicitudPendienteEjecutor
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
    #---Filtros
    sendKeys    //*[@id="mando"]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    captureEntirePageScreenshot
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    2s
    #---Tipo solicitud
    click    xpath=//*[@id="id_tipo_solicitud"]
    Sleep    1s
    click    //option[contains(text(),"Solicitud de Ingreso al Registro")]
    Sleep    1s
    #---Estado
    click    xpath=//*[@id="id_estado"]
    Sleep    1s
    click    //option[contains(text(),"Pendiente por Resolver Ejecutor")]
    Sleep    1s
    #---Busqueda
    Mouse Over    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button/span
    Sleep    2s
    captureEntirePageScreenshot
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button/span
    Sleep    4s
    #---Resultado
    sendKeys    //*[@id="mando"]/div[3]/div/div/div[1]/div/div[1]/div/strong    PAGE_DOWN
    captureEntirePageScreenshot
    Sleep    2s
    #---------------------------------------------------------------
    click    xpath=//*[@id="dropdownOpciones"]/button
    Sleep    3s
    captureEntirePageScreenshot
    Mouse Over    xpath=//*[@id="sol_14624303"]/li[8]/a
    click    xpath=//*[@id="sol_14624303"]/li[8]/a
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    1s
    click    xpath=//*[@id="xModal"]/div/div/div[1]/button
    #---------------------------------------------------------------
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
