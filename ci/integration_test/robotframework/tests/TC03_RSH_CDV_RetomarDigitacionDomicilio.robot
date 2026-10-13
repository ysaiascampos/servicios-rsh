*** Settings ***
Library           SeleniumLibrary
Resource          ResourceCDV.robot

*** Variables ***

*** Test Cases ***
TC03_RSH_CDV_RetomarDigitacionDomicilio
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
    click    link=Gestionar Solicitud
    Sleep    3s
    #---------------------------------------------------------------
    #---Pantalla: Cambio de Domicilio e Información de vivienda
    captureEntirePageScreenshot
    #---v1
    click    xpath=//*[@id="domViv_v1"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v1"]/option[contains(text(),"1. Casa")]
    Sleep    1s
    #---v2
    click    xpath=//*[@id="domViv_v2"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v2"]/option[contains(text(),"1. Propio pagado o pagándose")]
    Sleep    1s
    #---v3
    click    xpath=//*[@id="domViv_v3"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v3"]/option[contains(text(),"2. Arrendado")]
    Sleep    1s
    #---v4
    click    xpath=//*[@id="domViv_v4"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v4"]/option[contains(text(),"1. Red pública con medidor propio")]
    Sleep    1s
    #---v5
    click    xpath=//*[@id="domViv_v5"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v5"]/option[contains(text(),"1. Con llave dentro de la vivienda")]
    Sleep    1s
    #---v6
    click    xpath=//*[@id="domViv_v6"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v6"]/option[contains(text(),"Conectado a alcantarillado")]
    Sleep    1s
    #---v7
    click    xpath=//*[@id="domViv_v7"]
    Sleep    1s
    type    xpath=//*[@id="domViv_v7"]    3
    Sleep    1s
    captureEntirePageScreenshot
    #---v8
    click    xpath=//*[@id="domViv_v8"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v8"]/option[contains(text(),"1. Sí")]
    Sleep    1s
    #---v9
    click    xpath=//*[@id="domViv_v9"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v9"]/option[contains(text(),"1. Hormigón armado")]
    Sleep    1s
    click    xpath=//*[@id="domViv_v9ec"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v9ec"]/option[contains(text(),"1. Bueno")]
    #---v10
    click    xpath=//*[@id="domViv_v10"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v10"]/option[contains(text(),"2. Cerámico, flexit o similar")]
    Sleep    1s
    click    xpath=//*[@id="domViv_v10ec"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v10ec"]/option[contains(text(),"1. Bueno")]
    #---v11
    click    xpath=//*[@id="domViv_v11"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v11"]/option[contains(text(),"3. Planchas metálicas (zinc, cobre, etc) o fibrocemento (pizarreño)")]
    Sleep    1s
    click    xpath=//*[@id="domViv_v11ec"]
    Sleep    1s
    click    xpath=//*[@id="domViv_v11ec"]/option[contains(text(),"2. Aceptable")]
    captureEntirePageScreenshot
    Sleep    1s
    #---Boton Borrador
    click    xpath=//*[@id="guardarBorrador"]
    Sleep    1s
    captureEntirePageScreenshot
    Sleep    6s
    #---------------------------------------------------------------
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    4s
    captureEntirePageScreenshot
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
