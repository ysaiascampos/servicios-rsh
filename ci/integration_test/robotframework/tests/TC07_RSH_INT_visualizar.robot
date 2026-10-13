*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceM_INT.robot

*** Variables ***

*** Test Cases ***
TC07_RSH_Visualizar
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
    click    xpath=//*[@id="id_padre_solicitud"]
    type    xpath=//*[@id="id_padre_solicitud"]    ${SOL}
    Sleep    2s
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
    click    xpath=//*[@id="sol_15185462"]/li[1]/a
    sleep    4s
    #---Datos educacion
    click    id=icobtn_${USERinc}_educacion
    Sleep    5s
    click    //*[@id="modal_educacion17566707"]/div/div/div[3]/button
    #---------------------------------------------------------------
    #---Datos Salud
    sleep    5s
    click    xpath=//*[@id="icobtn_${USERinc}_salud"]
    Sleep    4s
    captureEntirePageScreenshot
    click    //*[@id="modal_salud${USERinc}"]/div/div/div[3]/button
    sleep    2s
    #---------------------------------------------------------------
    #---Datos Ingresos
    click    id=icobtn_${USERinc}_ingreso
    Sleep    1s
    captureEntirePageScreenshot
    click    //*[@id="modal_ingreso${USERinc}"]/div/div/div[3]/button
    sleep    5s
    #---------------------------------------------------------------
    #---Datos Ingresos Mensuales
    click    xpath=//*[@id="btn_${USERinc}_ingreso_mensual"]
    Sleep    2s
    captureEntirePageScreenshot
    click    //*[@id="modal_ingreso_mensual${USERinc}"]/div/div/div[3]/button
    sleep    5s
    #---------------------------------------------------------------
    #---BOTON GUARDAR COMENTADO
    scrollview    //*[@id="lstDocs"]/table/tbody/tr[1]/td[5]/button
    Mouse Over    //*[@id="lstDocs"]/table/tbody/tr[1]/td[5]/button
    sleep    5s
    click    //*[@id="bs-example-navbar-collapse-1"]/ul[2]/li/a
    close
    [Teardown]    Close Browser
