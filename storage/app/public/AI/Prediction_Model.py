import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import json
import mysql.connector
from prophet import Prophet

sales = pd.read_csv('public/storage/AI/sales-final2.csv')

# Convert Month column to datetime
sales['Month'] = sales['Month'].map(pd.to_datetime)

# Convert the specified columns to float
sales[sales.columns.tolist()[1:]] = sales[sales.columns.tolist()[1:]].replace(',', '', regex=True)
sales[sales.columns.tolist()[1:]] = sales[sales.columns.tolist()[1:]].astype(float)

# Fill the null values
sales = sales.fillna(method='ffill')


def predict(material):
    temp_df = temp_df = sales[['Month', f'{material}']]
    temp_df.rename(columns={'Month': 'ds', f'{material}': 'y'}, inplace=True)

    model = Prophet(seasonality_mode='multiplicative',
                    yearly_seasonality=3)
    model.fit(temp_df)

    future_dates = model.make_future_dataframe(periods=1, freq='M')
    predictions = model.predict(future_dates)

    sales_next_month = predictions[['yhat']].tail(1).values[0]
    return round(sales_next_month[0], 2)

try:
  mydb = mysql.connector.connect(
    host="localhost",
    user="root",
    password="123456",
    database="moh"
  )
  mycursor = mydb.cursor()
  output_types = ['حواصل', 'دجاج بياض لحم', 'فروج نيء', 'سودة', 'شرحة', 'صدر بجلدة', 'وردة', 'جناح',  'فخذ كامل', 'مجروم فرنسي']
  sql = "select type from output_production_types where type in (%s)" % (", ".join("'%s'" % v for v in output_types))
  
  mycursor.execute(sql)
  results = mycursor.fetchall()
  final_data = {}
  d = []
  for result in results:
      key = result[0]
      value = predict(result[0])
      final_data[key] = value

  array_of_objects = []
  for key, value in final_data.items():
        new_object = {"name": key, "value": value}
        array_of_objects.append(new_object)
        
  print(json.dumps(array_of_objects))
  mydb.commit()
  mycursor.close()
  
except mysql.connector.Error as error:
    print("Failed to insert record into Laptop table {}".format(error))
    
finally:
    if mydb.is_connected():
        mydb.close()


# for result in results:
#     print(json.dumps(result))


