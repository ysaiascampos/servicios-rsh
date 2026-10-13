*** Settings ***
Library           SeleniumLibrary
Resource          Resource_COM.robot

*** Test Cases ***
TC_RSH_COM_IngresoPensionAlimenticia
    [Documentation]    *Ingreso Pensión Alimenticia*
    mainSetup
    #---------------------------- Login RSH ---------------------------------------
    LoginToRSH    ${USER}[0]    ${PASSWORDS}[0]
    captureEntirePageScreenshot
    Sleep    5s
    #-------------------------- Información del Ciudadano ------------------------
    Click link    Ciudadano
    Sleep    2s
    Click link    Cambiar RUN Ciudadano
    captureEntirePageScreenshot
    Sleep    3s
    click    id=rut_usuario
    type    id=rut_usuario    ${RUTCIUDADANOS}[6]
    click    id=dv
    type    id=dv    ${DV_CIUDADANOS}[5]
    captureEntirePageScreenshot
    click    id=btn_consultar
    Sleep    5s
    #-------------------------- Seleccionar Trámite -------------------------------
    click    id=ir_tra_op5
    captureEntirePageScreenshot
    Sleep    5s
    click    xpath=//button[@type='submit']
    Sleep    5s
    #----------- Complemento de Información al Registro Social de Hogares ---------
    click    xpath=//*[@id="dataTables-example"]/tbody/tr[1]/td[1]
    Sleep    5s
    captureEntirePageScreenshot
    click    id=btnfin
    Sleep    5s
    #------ Seleccione la solicitud de complemento que desea llevar a cabo --------
    click    id=ir_com_op1
    Sleep    5s
    captureEntirePageScreenshot
    click    xpath=//button[@type='submit']
    Sleep    5s
    click    xpath=//button[@type='submit']
    Sleep    5s
    #----------- Complemento de Información al Registro Social de Hogares ---------
    click    id=ingresos1
    click    id=monto1
    type    id=monto1    96.150
    click    id=rut_causante1
    type    id=rut_causante1    23977612
    click    id=dv_causante1
    type    id=dv_causante1    4
    click    id=rut_receptor1
    type    id=rut_receptor1    16856503
    click    id=dv_receptor1
    type    id=dv_receptor1    8
    click    id=rut_pagadorpension1
    type    id=rut_pagadorpension1    ${RUTCIUDADANOS}[6]
    click    id=dv_pagadorpension1
    type    id=dv_pagadorpension1    ${DV_CIUDADANOS}[5]
    click    id=nombre_causante1
    type    id=nombre_causante1    MAGDALENA ESTELA GIL JIMENEZ
    click    id=nombre_receptor1
    type    id=nombre_receptor1    JIMENA ALEJANDRA JIMENEZ ORELLANA
    click    id=nombre_pagadorpension1
    type    id=nombre_pagadorpension1    MARIO ENRIQUE GIL AGUILERA
    Sleep    5s
    click    id=btnContinuar
    Sleep    5s
    click    xpath=(//button[@type='button'])[3]
    Sleep    5s
    #---------------------------- Adjuntar Documento -----------------------------
    click    xpath=//*[@id="frmxing"]/div/div[1]/ul[1]/div[1]/label
    Choose File    name=archivo    ${UPLOAD_DUMMY_DOCX_FILE}
    click    xpath=//*[@id="frmxing"]/div/div[2]/button
    Sleep    5s
    captureEntirePageScreenshot
    click    xpath=//*[@id="frmxing"]/div/div[1]/ul[1]/div[2]/label
    Choose File    name=archivo    ${UPLOAD_DUMMY_DOCX_FILE}
    click    xpath=//*[@id="frmxing"]/div/div[2]/button
    Sleep    5s
    captureEntirePageScreenshot
    click    xpath=//*[@id="frmxing"]/div/div[1]/ul[2]/div[1]/label
    Choose File    name=archivo    ${UPLOAD_DUMMY_DOCX_FILE}
    click    xpath=//*[@id="frmxing"]/div/div[2]/button
    captureEntirePageScreenshot
    Sleep    5s
    click    xpath=//*[@id="frmxing"]/div/button[2]
    #----------------------------Cerrar Sesión RSH---------------------------------
    CerrarSesion
