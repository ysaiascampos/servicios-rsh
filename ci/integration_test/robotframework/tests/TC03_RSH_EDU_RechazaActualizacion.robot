*** Settings ***
Library           SeleniumLibrary
Resource          ResourceM_EDU.robot

*** Variables ***

*** Test Cases ***
TC03_RSH_RechazaActualizacion
    [Documentation]    *Rechazar Solicitud*
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    #----------------------------Login RSH------------------------------
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
    click    xpath=(//button[@type='button'])[2]
    Sleep    5s
    click    id=id_tipo_solicitud
    Sleep    2s
    click    //option[contains(text(),"Actualización de Módulo Educación")]
    click    id=id_estado
    Sleep    2s
    click    //option[contains(text(),"Pendiente Entregar Documentos")]
    Sleep    3s
    captureEntirePageScreenshot
    click    xpath=(//button[@type='button'])[3]
    Sleep    3s
    captureEntirePageScreenshot
    click    xpath=(//button[@type='button'])[7]
    Sleep    5s
    captureEntirePageScreenshot
    click    link=Rechazar Solicitud
    sleep    5s
    click    //*[@id="observaciones"]
    type    //*[@id="observaciones"]    "Rechazar solicitud de prueba"
    sleep    6s
    click    //*[@id="xModal"]/div/div/div[1]/button
    sleep    5s
    #---------------------------------------------------------------------------
    #----------------------------Cerrar Sesión RSH------------------------------
    click    link=Cerrar sesión
    close
    [Teardown]    Close Browser
