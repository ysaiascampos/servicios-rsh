*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceREC.robot

*** Variables ***

*** Test Cases ***
TC07_RSH_REC_RectificaIngresoJubilacion
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    Set Global Variable    ${UPLOAD_DUMMY_DOCX_FILE}    ${CURDIR}/doc_prueba.pdf
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
    #-----------------------------------------------------------------
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/a
    Mouse Over    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/ul/li[2]/a
    Sleep    2s
    captureEntirePageScreenshot
    click    xpath=//*[@id="bs-example-navbar-collapse-1"]/ul[1]/li[2]/ul/li[2]/a
    Sleep    3s
    click    xpath=//*[@id="rut_usuario"]
    type    xpath=//*[@id="rut_usuario"]    ${RUTCIUDADANO5}
    Sleep    1s
    click    xpath=//*[@id="dv"]
    type    xpath=//*[@id="dv"]    ${DVCIUDADANO5}
    captureEntirePageScreenshot
    click    xpath=//*[@id="btn_consultar"]
    Sleep    3s
    #---------------------------------------------------------------
    #---Seleccion de tramite
    captureEntirePageScreenshot
    sendKeys    xpath=//*[@id="content"]/div[3]/div/h1    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="ir_tra_op4"]
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="content"]/form[2]/div/div/div/div[6]/div/button
    Sleep    3s
    #---------------------------------------------------------------
    #---Seleccion de Integrante
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div/section/div[3]/div/h3    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="3873899-2"]
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="btnfin"]
    Sleep    6s
    #---------------------------------------------------------------
    #---Seleccion Ingreso Jubilacion
    captureEntirePageScreenshot
    #sendKeys    xpath=//html/body/div[1]/div/section/div[3]/div/h3    PAGE_DOWN
    Sleep    1s
    click    xpath=/html/body/div[1]/div/section/form[2]/div[1]/div/div/div[3]/div[1]/label/input[2]
    captureEntirePageScreenshot
    click    xpath=//*[@id="content"]/form[2]/div[2]/div/button[2]
    Sleep    6s
    #---------------------------------------------------------------
    #---Confirmacion de Datos del Solicitado
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div/section/div[3]/div/h3    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="content"]/div[4]/div/form/div[2]/div/button[2]
    Sleep    6s
    #---------------------------------------------------------------
    #---Pantalla Rectificacion
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div/section/div[4]/div/form/div/div[3]/div/strong    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//html/body/div/div/section/div[4]/div/form/div/div[3]/div/div/div[12]/div[3]/input
    Sleep    1s
    click    xpath=//html/body/div/div/section/div[4]/div/form/div/div[3]/div/div/div[12]/div[2]/div/div/input
    type    xpath=//html/body/div/div/section/div[4]/div/form/div/div[3]/div/div/div[12]/div[2]/div/div/input    ${INGRESO460}
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton Continuar
    click    xpath=//*[@id="btnfin"]
    Sleep    3s
    #---------------------------------------------------------------
    #---Pantalla Solicitud Ingresada
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div/section/form[2]/blockquote/h4[1]/ol/li[3]/strong    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton Descargar Formulario
    click    xpath=//*[@id="frmup"]/blockquote/button
    Sleep    2s
    #---Boton Descargar Formulario
    click    xpath=//*[@id="frmup"]/blockquote/h4[3]/button[1]
    Sleep    4s
    #---------------------------------------------------------------
    #---Gestion de la Solicitud
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div/section/div[3]/form/div/div[1]/u/h3    PAGE_DOWN
    captureEntirePageScreenshot
    Sleep    1s
    #---Upload Documento 1
    click    xpath=//html/body/div/div/section/div[3]/form/div/div[1]/ul[1]/div[1]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="archivo"]    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmxing"]/div/div[2]/button
    Sleep    1s
    captureEntirePageScreenshot
    #---Upload Documento 2
    click    xpath=//html/body/div/div/section/div[3]/form/div/div[1]/ul[1]/div[2]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="archivo"]    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmxing"]/div/div[2]/button
    Sleep    1s
    captureEntirePageScreenshot
    #---Upload Documento 3
    click    xpath=//html/body/div/div/section/div[3]/form/div/div[1]/ul[2]/div[2]/label/input
    Sleep    1s
    Choose File    xpath=//*[@id="archivo"]    ${UPLOAD_DUMMY_DOCX_FILE}
    Sleep    1s
    click    xpath=//*[@id="frmxing"]/div/div[2]/button
    Sleep    1s
    captureEntirePageScreenshot
    #---Boton Continuar
    click    xpath=//*[@id="frmxing"]/div/button[2]
    Sleep    4s
    #---------------------------------------------------------------
    #--- Pantalla Solicitud en Tramitacion
    captureEntirePageScreenshot
    #sendKeys    xpath=//html/body/div/div/section/form[2]/blockquote/u/h3    PAGE_DOWN
    Sleep    1s
    captureEntirePageScreenshot
    click    xpath=//*[@id="frmup"]/blockquote/div/button
    Sleep    6s
    #---------------------------------------------------------------
    #---Busca el Registro para Rechazarlo
    captureEntirePageScreenshot
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="run"]
    type    xpath=//*[@id="run"]    ${RUTCIUDADANO5}
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    Select From List By Value    xpath=//*[@id="id_tipo_solicitud"]    77
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[contains(text(),"Pendiente por Resolver Ejecutor")]
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    6s
    sendKeys    xpath=//html/body/div/div[1]/section/div[4]/div[2]/div/div/div[1]/strong    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    captureEntirePageScreenshot
    click    xpath=//*[@id="dropdownOpciones"]/button
    Sleep    1s
    captureEntirePageScreenshot
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
