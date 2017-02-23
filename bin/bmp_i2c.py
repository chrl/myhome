#!/usr/bin/python

import Adafruit_BMP.BMP085 as BMP085
sensor = BMP085.BMP085(mode=BMP085.BMP085_ULTRAHIGHRES)

print('{"temp": '+ str(sensor.read_temperature()) + ', "press": '+str(sensor.read_pressure() / 133.322)+'}')