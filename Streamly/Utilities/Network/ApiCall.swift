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
        let url: String! = "http://streamlytv.com/api/auth/connecteddevice"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }
    
    @discardableResult
    public static func getContactInfoData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "http://streamlytv.com/api/auth/support"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func logOut(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "http://streamlytv.com/api/auth/logout"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .post, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getPrivacyData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "http://streamlytv.com/api/auth/privacypol"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]

        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }
    
    @discardableResult
    public static func getSearchData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "http://streamlytv.com/api/auth/search"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        // let param:[String:String] = ["search":"TNT"]
        
        let requestParameter = RequestParameter.createRequestParameter(url, method: .post, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getEPGData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "http://streamlytv.com/api/auth/channeldayschdeule"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getChannelList(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let url: String! = "http://streamlytv.com/api/auth/channellist"
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json"]
        let requestParameter = RequestParameter.createRequestParameter(url, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

}

