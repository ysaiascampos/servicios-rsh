*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceREC.robot

*** Variables ***

*** Test Cases ***
TC03_RSH_REC_AdjuntaDocsRectificacion
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    Set Global Variable    ${UPLOAD_DUMMY_DOCX_FILE}    ${CURDIR}/doc_prueba.pdf
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
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${RUTCIUDADANO}
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    click    xpath=//*[@id="id_tipo_solicitud"]/option[contains(text(),"Vehículo")]
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
    click    link=Rectificación de Propiedad de Vehículo
    Sleep    4s
    #---------------------------------------------------------------
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[2]/form[2]/div/div[3]/div[1]    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[2]/form[2]/div/div[4]/div[2]/div[2]/form/u/h3    PAGE_DOWN
    Sleep    1s
    #---Upload Formulario
    click    xpath=//*[@id="frmup"]/div[1]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="frmup"]/div[6]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmup"]/div[6]/div/div[2]/input
    Sleep    1s
    captureEntirePageScreenshot
    #---Upload Cedula
    click    xpath=//*[@id="frmup"]/div[2]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="frmup"]/div[6]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmup"]/div[6]/div/div[2]/input
    Sleep    1s
    captureEntirePageScreenshot
    #---Upload Padron
    click    xpath=//*[@id="frmup"]/div[4]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="frmup"]/div[6]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmup"]/div[6]/div/div[2]/input
    Sleep    1s
    captureEntirePageScreenshot
    #---------------------------------------------------------------
    #---Regreso a Bandeja Solicitudes
    Sleep    4s
    captureEntirePageScreenshot
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[1]/a
    Sleep    3s
    captureEntirePageScreenshot
    Sleep    1s
    #---------------------------------------------------------------
    #---Busca el Registro para Rechazarlo
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${RUTCIUDADANO}
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    click    xpath=//*[@id="id_tipo_solicitud"]/option[contains(text(),"Vehículo")]
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
    #---------------------------------------------------------------
    click    link=Cerrar sesión
    Sleep    1s
    captureEntirePageScreenshot
    close
    [Teardown]    Close Browser
