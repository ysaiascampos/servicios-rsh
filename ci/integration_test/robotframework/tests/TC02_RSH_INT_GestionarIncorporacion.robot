*** Settings ***
Library           SeleniumLibrary
Library           OperatingSystem
Resource          ResourceM2_INT.robot

*** Variables ***

*** Test Cases ***
TC02_RSH_GestionarIncorporacion
    [Setup]    Run Keywords    Open Browser    ${URL}    ${BROWSER}
    ...    AND    Set Selenium Speed    ${SELSPEED}
    Set Global Variable    ${UPLOAD_DUMMY_DOCX_FILE}    ${CURDIR}/doc_prueba.pdf
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
    click    //*[@id="loginform"]/div[3]/div/button
    Sleep    2s
    captureEntirePageScreenshot
    #-------------------------------------------------------------------
    sendKeys    xpath=//*[@id="mando"]/div[2]    PAGE_DOWN
    sleep    2s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[5]/button
    Sleep    1s
    click    xpath=//*[@id="id_tipo_solicitud"]
    click    xpath=//*[@id="id_tipo_solicitud"]/option[contains(text(),"Incorporación de nuevo integrante")]
    Sleep    1s
    click    xpath=//*[@id="id_estado"]
    click    xpath=//*[@id="id_estado"]/option[contains(text(),"Pendiente Entregar Documentos")]
    Sleep    1s
    click    xpath=//*[@id="mando"]/div[2]/div/div/div[2]/form/div[1]/div/div[6]/button
    Sleep    8s
    sendKeys    xpath=//*[@id="mando"]/div[2]    PAGE_DOWN
    Sleep    1s
    click    xpath=//*[@id="dropdownOpciones"]/button
    sleep    3s
    click    link=Gestionar Solicitud
    sleep    5s
    #------prueba
    ${USERinc}=    Get Text    //html/body/div/div/section/div[2]/form/div/div[4]/div[2]/div[2]/table/tbody/tr/td[1]    #---Datos educacion
    click    id=icobtn_${USERinc}_educacion
    Sleep    5s
    Click Element    xpath=//*[@id="div_${USERinc}_e1"]/div/div[1]/div/label
    sleep    6s
    #click    xpath=//button[text()="Sí"]
    captureEntirePageScreenshot
    click    id=btn_guardar_educacion${USERinc}
    #---------------------------------------------------------------
    #---Datos Salud
    sleep    5s
    click    xpath=//*[@id="icobtn_${USERinc}_salud"]
    Sleep    4s
    #---S1. ¿Esta persona tiene alguna de las siguientes condiciones de forma permanente?
    click    xpath=//div[@id='div_${USERinc}_s1']/div[2]/div/label
    click    xpath=//div[@id='div_${USERinc}_s2a']/div/div/label
    click    xpath=//div[@id='div_${USERinc}_s2b']/div/div/label
    click    xpath=//div[@id='div_${USERinc}_s2c']/div/div/label
    click    xpath=//div[@id='div_${USERinc}_s2d']/div/div/label
    click    xpath=//div[@id='div_${USERinc}_s2e']/div/div/label
    captureEntirePageScreenshot
    click    id=btn_guardar_salud${USERinc}
    sleep    2s
    #---------------------------------------------------------------
    #---Datos Ingresos
    click    id=icobtn_${USERinc}_ingreso
    Sleep    1s
    #---O1. ¿Está trabajando?
    click    xpath=//*[@id="div_${USERinc}_o1"]/div/div[1]/div/label
    #---O2. ¿Cuál es su ocupación actual?
    click    xpath=//*[@id="${USERinc}_o2"]
    click    //option[contains(text(),"3. Empleado u obrero")]
    Sleep    1s
    #---O3. Códigos Rama
    click    xpath=//*[@id="${USERinc}_o3"]
    click    //option[contains(text(),"5.Construcción")]
    Sleep    1s
    #---O4. Temporalidad / Permanencia
    click    xpath=//*[@id="${USERinc}_o4"]
    click    //option[contains(text(),"1.Permanente")]
    Sleep    1s
    #---O5. Código Contrato
    click    xpath=//*[@id="div_${USERinc}_o5"]/div/div[1]/div/label
    Sleep    1s
    captureEntirePageScreenshot
    #---O6. Códigos Relación Contractual
    click    xpath=//*[@id="${USERinc}_o6"]
    click    //option[contains(text(),"1.Plazo indefinido")]
    Sleep    1s
    #---O7. ¿Cuántas horas a la semana trabaja?
    click    id=${USERinc}_o7
    type    id=${USERinc}_o7    45
    Sleep    1s
    #---Y3. Otros ingresos anuales
    click    id=${USERinc}_y3
    type    id=${USERinc}_y3    0
    Sleep    1s
    #---
    captureEntirePageScreenshot
    click    id=btn_guardar_ingreso${USERinc}
    sleep    5s
    #---------------------------------------------------------------
    #---Datos Ingresos Mensuales
    click    xpath=//*[@id="btn_${USERinc}_ingreso_mensual"]
    Sleep    2s
    #---Ingresos del trabajo
    click    id=${USERinc}_y1_m1
    type    id=${USERinc}_y1_m1    ${INGRESO460}
    click    id=${USERinc}_y1_m7
    type    id=${USERinc}_y1_m7    ${INGRESO460}
    Sleep    1s
    click    id=${USERinc}_y1_m2
    type    id=${USERinc}_y1_m2    ${INGRESO460}
    click    id=${USERinc}_y1_m8
    type    id=${USERinc}_y1_m8    ${INGRESO460}
    Sleep    1s
    click    id=${USERinc}_y1_m3
    type    id=${USERinc}_y1_m3    ${INGRESO460}
    click    id=${USERinc}_y1_m9
    type    id=${USERinc}_y1_m9    ${INGRESO460}
    Sleep    1s
    click    id=${USERinc}_y1_m4
    type    id=${USERinc}_y1_m4    ${INGRESO460}
    click    id=${USERinc}_y1_m10
    type    id=${USERinc}_y1_m10    ${INGRESO460}
    Sleep    1s
    click    id=${USERinc}_y1_m5
    type    id=${USERinc}_y1_m5    ${INGRESO460}
    click    id=${USERinc}_y1_m11
    type    id=${USERinc}_y1_m11    ${INGRESO460}
    Sleep    1s
    click    id=${USERinc}_y1_m6
    type    id=${USERinc}_y1_m6    ${INGRESO460}
    click    id=${USERinc}_y1_m12
    type    id=${USERinc}_y1_m12    ${INGRESO460}
    captureEntirePageScreenshot
    #---Jubilaciones
    click    id=${USERinc}_y2_m1
    type    id=${USERinc}_y2_m1    ${INGRESO0}
    click    id=${USERinc}_y2_m7
    type    id=${USERinc}_y2_m7    ${INGRESO0}
    Sleep    1s
    click    id=${USERinc}_y2_m2
    type    id=${USERinc}_y2_m2    ${INGRESO0}
    click    id=${USERinc}_y2_m8
    type    id=${USERinc}_y2_m8    ${INGRESO0}
    Sleep    1s
    click    id=${USERinc}_y2_m3
    type    id=${USERinc}_y2_m3    ${INGRESO0}
    click    id=${USERinc}_y2_m9
    type    id=${USERinc}_y2_m9    ${INGRESO0}
    Sleep    1s
    click    id=${USERinc}_y2_m4
    type    id=${USERinc}_y2_m4    ${INGRESO0}
    click    id=${USERinc}_y2_m10
    type    id=${USERinc}_y2_m10    ${INGRESO0}
    Sleep    1s
    click    id=${USERinc}_y2_m5
    type    id=${USERinc}_y2_m5    ${INGRESO0}
    click    id=${USERinc}_y2_m11
    type    id=${USERinc}_y2_m11    ${INGRESO0}
    Sleep    1s
    click    id=${USERinc}_y2_m6
    type    id=${USERinc}_y2_m6    ${INGRESO0}
    click    id=${USERinc}_y2_m12
    type    id=${USERinc}_y2_m12    ${INGRESO0}
    sleep    2s
    captureEntirePageScreenshot
    #---
    click    id=btn_guardar_ingreso_mensual${USERinc}
    sleep    5s
    click    //button[contains(text(),"Confirmar")]
    #-----------
    sendKeys    xpath=//*[@id="content"]/footer/div/div[3]    PAGE_DOWN
    Sleep    1s
    #---Documento formulario soli
    click    xpath=//html/body/div/div/section/div[2]/form/div/div[5]/div[2]/form/div[1]/div[1]/label/input
    sleep    3s
    Choose File    xpath=//*[@id="frmup"]/div[3]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    sleep    3s
    click    xpath=//input[@value='Subir Archivo']
    sleep    5s
    #---Documento formulario soli
    click    xpath=//*[@id="frmup"]/div[1]/div[2]/label/input
    sleep    3s
    Choose File    xpath=//*[@id="frmup"]/div[3]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    sleep    3s
    click    xpath=//input[@value='Subir Archivo']
    sleep    5s
    #---Documento judicial
    click    xpath=//*[@id="frmup"]/div[2]/div[3]/label/input
    sleep    3s
    Choose File    xpath=//*[@id="frmup"]/div[3]/div/div[1]/input    ${UPLOAD_DUMMY_DOCX_FILE}
    sleep    3s
    click    xpath=//input[@value='Subir Archivo']
    sleep    5s
    #----------------------------Cerrar Sesión RSH------------------------------
    close
    [Teardown]    Close Browser
