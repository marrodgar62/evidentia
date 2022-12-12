from typing_extensions import Self
import unittest
from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
import time


#Xpath elements
input_login = '/html/body/div[1]/div[2]/div/form/div[2]/input'
input_password = '/html/body/div[1]/div[2]/div/form/div[3]/input'
input_submit = '/html/body/div[1]/div[2]/div/form/div[5]/div[2]/button'
crear_tarea = '/html/body/div[1]/aside[1]/div/nav[2]/ul/li[3]/a/p'
input_titulo = '/html/body/div[1]/div[1]/section/div/form/div/div/div/div/div/div[1]/input'
input_descripcion = '/html/body/div[1]/div[1]/section/div/form/div/div/div/div/div/div[2]/div/div[3]/div[2]'
submit_tarea = '/html/body/div[1]/div[1]/section/div/form/div/div/div/div/div/div[3]/button'
tarea_con_exito = '/html/body/div[2]/div/div[2]'
list_tarea_alumno = '/html/body/div[1]/aside[1]/div/nav[2]/ul/li[6]/a/p'
ordenar_id= '/html/body/div[1]/div[1]/section/div/div/div/div[2]/div/div/div/div/div[2]/div/table/thead/tr/th[1]'
titulo_list_tarea = '/html/body/div[1]/div[1]/section/div/div/div/div[2]/div/div/div/div/div[2]/div/table/tbody/tr[1]/td[2]/a'
#Driver options



#Functions to click and send keys
def click_element(driver, element):
    time.sleep(1)
    WebDriverWait(driver, 2)\
        .until(EC.element_to_be_clickable((By.XPATH, element))).click()

def send_keys(driver, element, keys):
    time.sleep(1)
    WebDriverWait(driver, 2)\
        .until(EC.element_to_be_clickable((By.XPATH, element))).send_keys(keys)
        
def check_element(driver, element, keys):
    time.sleep(1)
    WebDriverWait(driver, 2)\
        .until(EC.assertEqual(keys))


class Suite(unittest.TestCase):

    def test_create_incidence_positive(self):
        #Driver options
        options = webdriver.ChromeOptions()
        driver = webdriver.Chrome(options=options)
        driver.get("http://localhost/21/login")
        driver.maximize_window()
        time.sleep(2)

        #Login
        send_keys(driver, input_login, 'alumno1')
        send_keys(driver, input_password, 'alumno1')
        click_element(driver, input_submit)

        #Crear incidencia
        click_element(driver, crear_tarea)
        send_keys(driver, input_titulo, 'Titulo de prueba')
        send_keys(driver, input_descripcion, 'Descripcion de prueba selenium')
        click_element(driver, submit_tarea)

        #Comprobar que se ha creado la incidencia
        self.assertEqual(driver.find_element(By.XPATH,tarea_con_exito).text, 'Tarea creada con éxito.')
        driver.quit()
    
    def test_list_tarea(self):
        #Driver options
        options = webdriver.ChromeOptions()
        #options.add_argument('--headless')
        driver = webdriver.Chrome(options=options)
        driver.get("http://localhost/21/login")
        driver.maximize_window()
        time.sleep(2)

        #Login
        send_keys(driver, input_login, 'alumno1')
        send_keys(driver, input_password, 'alumno1')
        click_element(driver, input_submit)

        #Gestionar incidencia
        click_element(driver, list_tarea_alumno)
        #Ordenar por ID
        click_element(driver, ordenar_id)
        #Comprobar Titulo
        self.assertEqual(driver.find_element(By.XPATH, titulo_list_tarea).text, 'Titulo de prueba')
        driver.quit()