*** Settings ***
Library           SeleniumLibrary
Resource          ResourceGES.robot

*** Variables ***

*** Test Cases ***
TC05_RSH_GES_GestionSolicitudPendienteEjecutor
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    # open    ${URL}
    Maximize Browser Window
    captureEntirePageScreenshot
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
    #Mouse Over    xpath=//*[@id="sol_15182427"]/li[2]/a
    #click    xpath=//*[@id="sol_15182427"]/li[2]/a
    Mouse Over    link=Gestionar Solicitud
    captureEntirePageScreenshot
    Sleep    1s
    click    link=Gestionar Solicitud
    Sleep    6s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    sendKeys    xpath=//*[@id="encp0"]/div[1]    PAGE_DOWN
    captureEntirePageScreenshot
    Sleep    1s
    sendKeys    xpath=//html/body/div/div/section/div[2]/form/div/div[3]/div[2]/div/table/thead/tr/th[1]    PAGE_DOWN
    captureEntirePageScreenshot
    Sleep    1s
    sendKeys    xpath=//html/body/div/div/section/div[2]/form/div/div[5]/div[1]    PAGE_DOWN
    Sleep    1s
    #---Boton Aprobar Solicitud
    Mouse Over    xpath=//*[@id="ecnormal"]/button[2]
    #click    xpath=//*[@id="ecnormal"]/button[2]
    #---Retorna al inicio
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    4s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
