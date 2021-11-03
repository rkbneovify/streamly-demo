//
//  ContectInfoViewModel.swift
//  Streamly
//
//  Created by Rohit Singh on 6/10/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

class ContactDataViewModel: NSObject {

    var privacyResponseData:((PrivacyResponseData)->())?
    var contactData:((ContactResponse)->())?
    var logoutdata:((LogoutResponseData)->())?
    weak var delegate:HandleError?
   

    func getContactData() {
        ApiCall.getContactInfoData(success: { data, _
            in
            if let contactModel = DataParserContact.parseContactData(data!) {
                self.contactData!((contactModel))
                //debugPrint(contactModel)
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
                return
            }
            //debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
            if code == 401 {
                Constant.logOut()
            }
        }
    }

    func logOUT() {
        ApiCall.logOut(success: { data, _
            in
            if let logoutModel = DataParserLogOut.parseContactData(data!) {
                self.logoutdata!((logoutModel))
                //debugPrint(logoutModel)
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
                return
            }
            //debugPrint("failure")
            self.delegate?.handleError(error?.localizedDescription ?? "")
            if code == 401 {
                Constant.logOut()
            }
        }
    }

    func getPrivacy() {
           ApiCall.getPrivacyData(success: { data, _
               in
               if let privacyModel = DataParserPrivacy.parseContactData(data!) {
                   self.privacyResponseData!((privacyModel))
                   //print(privacyModel)
               }
               //debugPrint("Success")
           }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
                return
            }
               //debugPrint("failure")
               self.delegate?.handleError(error?.localizedDescription ?? "")
            if code == 401 {
                Constant.logOut()
            }
           }
       }

    func getTerms() {
              ApiCall.getTermsData(success: { data, _
                  in
                  if let privacyModel = DataParserPrivacy.parseContactData(data!) {
                      self.privacyResponseData!((privacyModel))
                      //print(privacyModel)
                  }
                  //debugPrint("Success")
              }) { (error, data, code)  in
                if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                    Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                    Utility.logOut()
                    return
                }
                  //debugPrint("failure")
                  self.delegate?.handleError(error?.localizedDescription ?? "")
                if code == 401 {
                    Constant.logOut()
                }
              }
          }
}

class DataParserContact {

    static func parseContactData(_ data: Data) -> ContactResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(ContactResponse.self, from: data)


            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
}

class DataParserLogOut {

    static func parseContactData(_ data: Data) -> LogoutResponseData? {
        do {
            let contactModelElement = try JSONDecoder().decode(LogoutResponseData.self, from: data)


            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
}

class DataParserPrivacy {

    static func parseContactData(_ data: Data) -> PrivacyResponseData? {
        do {
            let contactModelElement = try JSONDecoder().decode(PrivacyResponseData.self, from: data)


            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
}




