*** Settings ***
Library           SeleniumLibrary
Resource          ResourceM2_COM.robot

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
    type    xpath=//*[@id="login-password"]    ${PASSWORDS}
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="loginform"]/div[3]/div/button
    Sleep    2s
    captureEntirePageScreenshot
    #-------------------------------------------------------------------
    click    xpath=(//button[@type='button'])[2]
    Sleep    5s
    click    id=id_tipo_solicitud
    Sleep    2s
    click    xpath=//*[@id="id_tipo_solicitud"]
    sleep    5s
    click    //*[@id="id_tipo_solicitud"]/option[16]
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[6]
    Sleep    3s
    captureEntirePageScreenshot
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    3s
    click    xpath=//html/body/div/div[1]/section/div[4]/div[3]/div/div/div[2]/div[2]/div/table/tbody/tr[1]/td[12]/div/button
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
