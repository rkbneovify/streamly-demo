//
//  ApiCall.swift
//  Streamly
//
//  Created by Rohit Singh on 5/12/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//


import Alamofire

//streamlytv.com/api/

open class ApiCall {
    

    @discardableResult
    public static func getRegistredDeviceData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/connecteddevice"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }
    
    @discardableResult
    public static func getContactInfoData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/support"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func logOut(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/logout"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .post, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getPrivacyData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/privacypol"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }
    
    @discardableResult
    public static func getSearchData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/search"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        // let param:[String:String] = ["search":"TNT"]
        
        let requestParameter = RequestParameter.createRequestParameter(url, method: .post, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getEPGData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/channeldayschdeule"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getChannelList(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/channelgenre"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
       public static func getTermsData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
           let url: String! = "https://streamlytv.com/api/auth/termofuse"
          // let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
           let headers = ["Accept": "application/json"]
           let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
           return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
       }

    @discardableResult
    public static func getRecentData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/my_recents"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func postRecentData(_ ids:String,success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
          let url: String! = "https://streamlytv.com/api/auth/addtorecent"
          let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
          let param:[String:String] = ["channelids":ids]
          let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
          let requestParameter = RequestParameter.createRequestParameter(url, method: .post, headers: headers, parameters: param)
          return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
      }

    @discardableResult
    public static func postAddress(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "https://streamlytv.com/api/auth/geofence"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        debugPrint(token)
        let param:[String:String] = ["latitude":"\(Address.sharedInstance.latitude ?? 0.0)","longitude":"\(Address.sharedInstance.longitude ?? 0.0)"]
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        let requestParameter = RequestParameter.createRequestParameter(url, method: .post, headers: headers, parameters: param)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }
    
    
}

