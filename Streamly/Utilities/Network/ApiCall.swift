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
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getConnectedDevice.rawValue, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }
    
    @discardableResult
    public static func getContactInfoData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]

        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getContactInfo.rawValue, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func logOut(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json" ,"User-Agent": userAgent]

        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.logOut.rawValue, method: .post, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getPrivacyData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]

        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.privacyData.rawValue, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }
    
    @discardableResult
    public static func getSearchData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        // let param:[String:String] = ["search":"TNT"]
        
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getSearchData.rawValue, method: .post, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getEPGData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getEpgData.rawValue, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func getChannelList(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getChannelGenerics.rawValue, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
       public static func getTermsData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {

           let headers = ["Accept": "application/json","User-Agent": userAgent]
           let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getTermsData.rawValue, method: .get, headers: headers, parameters: nil)
           return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
       }

    @discardableResult
    public static func getRecentData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getRecentData.rawValue, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func postRecentData(_ ids:String,success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
          let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
          let param:[String:String] = ["channelids":ids]
          let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
          let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.addToRecent.rawValue, method: .post, headers: headers, parameters: param)
          return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
      }

    @discardableResult
    public static func postAddress(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        //debugPrint(token)
        let param:[String:String] = ["latitude":"\(Address.sharedInstance.latitude ?? 0.0)","longitude":"\(Address.sharedInstance.longitude ?? 0.0)"]
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getGeoFenceData.rawValue, method: .post, headers: headers, parameters: param)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func postOutsideAddress(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        //debugPrint(token)
        let param:[String:String] = ["latitude":"\(Address.sharedInstance.latitude ?? 0.0)","longitude":"\(Address.sharedInstance.longitude ?? 0.0)","deviceid":deviceId ?? ""]
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.postOutsideAdress.rawValue, method: .post, headers: headers, parameters: param)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }


    ///http://127.0.0.1:8000/api/auth/outsidelocationplay"
    
    @discardableResult
    public static func getQRData(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let headers = ["Accept": "application/json","User-Agent": userAgent]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getQrCodeData.rawValue, method: .get, headers: headers, parameters: nil)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func setCallSign(_ callSign:String, _ insideGeoFence:Bool,success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let param:[String:String] = ["sr":Constant.fetchPixel(),"callsign":callSign,"inside_geofence":"\(insideGeoFence)"]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.setCallSign.rawValue, method: .post, headers: headers, parameters: param)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func setPlayTimer(_ callSign:String, _ insideGeoFence:Bool,success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let param:[String:String] = ["sr":Constant.fetchPixel(),"callsign":callSign,"inside_geofence":"\(insideGeoFence)"]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.setplayTimer.rawValue, method: .post, headers: headers, parameters: param)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
    public static func setGAError(_ errorMsg:String, _ errorCode:String, _ callSign:String, _ insideGeoFence:Bool,success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
        let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
        let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
        let param:[String:String] = ["sr":Constant.fetchPixel(),"callsign":callSign,"inside_geofence":"\(insideGeoFence)","errmsg":errorMsg,"errcode":errorCode]
        let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.playererror.rawValue, method: .post, headers: headers, parameters: param)
        return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
    }

    @discardableResult
      public static func getDrmKey(success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {
          let token  = Utility.retriveStringObject(key: Constant.UserDefault.token)
          let headers = ["Authorization": "Bearer \(token)","Accept": "application/json","User-Agent": userAgent]
          let requestParameter = RequestParameter.createRequestParameter(Constant.baseURL + API.getdrmkey.rawValue, method: .get, headers: headers, parameters: nil)
          return NetworkManagerWrapper.makeRequest(requestParameter, success: success, failure: failure)
      }
}

