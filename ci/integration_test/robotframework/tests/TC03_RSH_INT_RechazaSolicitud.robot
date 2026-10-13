*** Settings ***
Library           SeleniumLibrary
Resource          ResourceM_INT.robot

*** Variables ***

*** Test Cases ***
TC03_RSH_RechazaSolitud
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
    click    //option[contains(text(),"Incorporación de nuevo integrante")]
    click    id=id_estado
    Sleep    2s
    click    //option[contains(text(),"Pendiente por Resolver Ejecutor")]
    Sleep    3s
    captureEntirePageScreenshot
    click    xpath=(//button[@type='button'])[3]
    Sleep    3s
    captureEntirePageScreenshot
    click    xpath=(//button[@type='button'])[7]
    Sleep    5s
    captureEntirePageScreenshot
    click    link=Rechazar Solicitud
    Mouse Over    xpath=//*[@id="observaciones"]
    type    id="observaciones"    "Rechazar solicitud de prueba"
    click    xpath=//*[@id="frmAsignar1"]/div[2]/div/div[2]/button
    Sleep    5s
    #---------------------------------------------------------------------------
    #----------------------------Cerrar Sesión RSH------------------------------
    CerrarSesion
    close
    [Teardown]    Close Browser
