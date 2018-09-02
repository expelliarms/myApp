#!/usr/bin/python

print


import numpy as np
import csv
from scipy.sparse import csr_matrix
from scipy.sparse import csc_matrix
import math as mt
from sparsesvd import sparsesvd

import mysql.connector

# Import modules for CGI handling 
import cgi, cgitb
import json



# Create instance of FieldStorage 
input_data = cgi.FieldStorage()

# Get data from fields
user_id = input_data["user_id"].value
#user_id = 90

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  passwd="12345678",
  database="vodafone"
)

#constants defining the dimensions of our User Rating Matrix (URM)
MAX_PID = 300
MAX_UID = 200

def readUrm():
	urm = np.zeros(shape=(MAX_UID,MAX_PID), dtype=np.float32)
        mycursor = mydb.cursor()

        mycursor.execute("SELECT user_id, offer_id FROM user_history WHERE 1")

        myresult = mycursor.fetchall()

        for row in myresult:
	  urm[int(row[0]), int(row[1])] = 1.0
	return csr_matrix(urm, dtype=np.float32), csc_matrix(urm, dtype=np.float32)

def readUsersTest():
	uTest = dict()
	uTest[int(user_id)] = list()

	return uTest

def getMoviesSeen():
	moviesSeen = dict()
        mycursor = mydb.cursor()

        mycursor.execute("SELECT user_id, offer_id FROM user_history WHERE user_id=" + str(user_id))

        myresult = mycursor.fetchall()

        for row in myresult:
		try:
			moviesSeen[int(row[0])].append(int(row[1]))
		except:
			moviesSeen[int(row[0])] = list()
			moviesSeen[int(row[0])].append(int(row[1]))
	return moviesSeen

def computeSVD(urm, urm_csc, K):
	U, s, Vt = sparsesvd(urm_csc, K)

	dim = (len(s), len(s))
	S = np.zeros(dim, dtype=np.float32)
	for i in range(0, len(s)):
		S[i,i] = mt.sqrt(s[i])

	U = csr_matrix(np.transpose(U), dtype=np.float32)
	S = csr_matrix(S, dtype=np.float32)
	Vt = csr_matrix(Vt, dtype=np.float32)

	return U, S, Vt


def computeEstimatedRatings(urm, U, S, Vt, uTest, moviesSeen, K, test):
	rightTerm = S*Vt 

	estimatedRatings = np.zeros(shape=(MAX_UID, MAX_PID), dtype=np.float16)
	for userTest in uTest:
		prod = U[userTest, :]*rightTerm

		#we convert the vector to dense format in order to get the indices of the movies with the best estimated ratings 
		estimatedRatings[userTest, :] = prod.todense()
		recom = (-estimatedRatings[userTest, :]).argsort()[:15]
		for r in recom:
			if userTest in moviesSeen:
			        if r not in moviesSeen[userTest]:
			 		uTest[userTest].append(r)

				#if len(uTest[userTest]) == 5:
					#break
					

	return uTest

def main():
	K = 2
	urm, urm_csc = readUrm()
	U, S, Vt = computeSVD(urm, urm_csc, K)
	uTest = readUsersTest()
	moviesSeen = getMoviesSeen()
        result = {}
        for key, value in moviesSeen.iteritems():
          value.append(0)
          mycursor = mydb.cursor()
          mycursor.execute("SELECT offerId, offerTitle FROM offers WHERE offerId IN " + str(tuple(value)))
          myresult = mycursor.fetchall()
          result["past"] = []
          for row in myresult:
            result["past"].append(row[1])
	uTest = computeEstimatedRatings(urm_csc, U, S, Vt, uTest, moviesSeen, K, True)
        for key, value in uTest.iteritems():
          mycursor = mydb.cursor()
          result["future"] = []
          if len(value) == 0:
            mycursor.execute("SELECT offerId, offerTitle FROM offers WHERE offerId=170")
            myresult = mycursor.fetchall()
            for row in myresult:
	      result["future"].append(row[1])
            mycursor.execute("SELECT offerId, offerTitle FROM offers WHERE 1 LIMIT 10")
          else:
            mycursor.execute("SELECT offerId, offerTitle FROM offers WHERE offerId IN " + str(tuple(value)) + "ORDER BY FIELD (offerId, "+ str(tuple(value))[1:-1]+")")
          myresult = mycursor.fetchall()
          for row in myresult:
            result["future"].append(row[1])
        print json.dumps(result)
        #print result

main()
