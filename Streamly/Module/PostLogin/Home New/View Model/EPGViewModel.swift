//
//  ChannelList+Program.swift
//  Streamly
//
//  Created by Rohit Singh on 6/23/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation

class EPGViewModel: NSObject {
    
    var epgData:((EPGResponse)->())?
    var geoData:((GeoFenceAddress)->())?
    var drmData:((DrmKey)->())?
    var recentData:((RecentData)->())?
    var myChannelList: ((MychannelData) -> ())?
    var channelData:((ChannelListResponse)->())?
    var currentPage = 1
    var lastPage = 10
    var isPageExist = true
    weak var delegate:HandleError?
    
    func getEPGData(_ page: Int = 1,_ genere: String = "All") {
        guard currentPage <= lastPage else {
            delegate?.successAPI()
            return
        }
        guard isPageExist else {
            delegate?.successAPI()
            return
        }
        ApiCall.getEPGData(page, genere, success: { data, _
            in
            if let `data` = data, let epgModel = EPGDataParser.parseContactData(data) {
                self.epgData?((epgModel))
                self.lastPage = epgModel.data?.lastPage ?? 0
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            } else {
                print("decode error")
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                if failedData.message == "The token has been blacklisted" {
                    self.delegate?.handleError(failedData.message ?? "", 401)
                } else {
                    self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                }
                return
            } else {
               // self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            }
            //debugPrint("failure")
           
            
//            if code == 401 {
//                Constant.logOut()
//            }
        }
    }
    
    func getMyChannelList() {
        ApiCall.getMyChannelList(success: { data, _
            in
            if let `data` = data, let model: MychannelData = EPGDataParser.parseData(data) {
                self.myChannelList?(model)
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", code ?? 0)
                    return
                }
            //self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            //debugPrint("failure")
            // self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    func getChannelList() {
        ApiCall.getChannelList(success: { data, _
            in
            if let `data` = data, let epgModel = EPGDataParser.parseChannelData(data) {
                self.channelData?((epgModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", code ?? 0)
                return
                }
            //self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            debugPrint("failure")
            // self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    func getRecentData() {
        ApiCall.getRecentData(success: { data, _
            in
            if let `data` = data,let recentModel = EPGDataParser.parserecentData(data) {
                self.recentData!((recentModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", code ?? 0)
                    return
                }
            //self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            //debugPrint("failure")
            // self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    func postRecentData(_ ids:String) {
        ApiCall.postRecentData(ids,success: { data, _
            in
            if let `data` = data, let recentModel = EPGDataParser.parserecentData(data) {
                self.recentData!((recentModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                return
            }
         //   self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            //debugPrint("failure")
            // self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    func postAddressData() {
        ApiCall.postAddress(success: { data, _
            in
            if let `data` = data, let geoModel = EPGDataParser.parseGeoFenceData(data) {
                self.geoData!((geoModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            
            if error == nil {
                if let `data` = data {
                    if let geoModel = EPGDataParser.parseGeoFenceData(data) {
                       // self.geoData!((geoModel))
                        ////debugPrint(epgModel)
                        self.delegate?.handleError(geoModel.popupmsg ?? "", code ?? 0)
                        return
                    } else if let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                        self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                        return
                    }
                }
                
               // self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
                
            }
            
            //            debugPrint("failure")
            //            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    func postOutsideAddress()
    {
        ApiCall.postOutsideAddress(success: { data, _
            in
            if let `data` = data, EPGDataParser.parseGeoFenceData(data) != nil {
                // self.geoData!((geoModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                return
            }
           // self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            //            debugPrint("failure")
            //            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    func getDrmKeys()
    {
        ApiCall.getDrmKey(success: { data, _
            in
            if let `data` = data, let drmData = EPGDataParser.parseDrmKeyData(data) {
                self.drmData!(drmData)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                return
            }
          //  self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            //            debugPrint("failure")
            //            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    
    func postCallSign(_ callSign:String, _ outSideGeoFence:Bool)
    {
        ApiCall.setCallSign(callSign, outSideGeoFence,success: { data, _
            in
            if let `data` = data, EPGDataParser.parseGeoFenceData(data) != nil {
                debugPrint("")
                // self.geoData!((geoModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                return
            }
           // self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            debugPrint("failure")
            //            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    func postgaError(_ callSign:String, _ outSideGeoFence:Bool, _ errorMsg:String,_ errorCode:String)
    {
        ApiCall.setGAError(errorMsg , errorCode , callSign, outSideGeoFence,success: { data, _
            in
            if let `data` = data, EPGDataParser.parseGeoFenceData(data) != nil {
                debugPrint("")
                // self.geoData!((geoModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) , failedData.response_code == 401 {
                self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                return
            }
           // self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            debugPrint("failure")
            //            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
    
    
    func setPlayTimer(_ callSign:String, _ outSideGeoFence:Bool)
    {
        ApiCall.setPlayTimer(callSign, outSideGeoFence,success: { data, _
            in
            if let `data` = data, EPGDataParser.parseGeoFenceData(data) != nil {
                debugPrint("")
                self.delegate?.successAPI()
                // self.geoData!((geoModel))
                ////debugPrint(epgModel)
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let `data` = data, let failedData: FailedResponse = DataParser.parseReponseToModel(data) {
                    self.delegate?.handleError(failedData.message ?? "", failedData.response_code ?? 0)
                return
            }
           // self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            debugPrint("failure")
            //            self.delegate?.handleError(error?.localizedDescription ?? "")
        }
    }
}

class EPGDataParser {
    
    static func parseContactData(_ data: Data) -> EPGResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(EPGResponse.self, from: data)
            
            
            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
    
    static func parseData<T: Codable>(_ data: Data) -> T? {
        do {
            let contactModelElement = try JSONDecoder().decode(T.self, from: data)
            
            
            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
    
    static func parseChannelData(_ data: Data) -> ChannelListResponse? {
        do {
            
            let contactModelElement = try JSONDecoder().decode(ChannelListResponse.self, from: data)
            
            
            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
    
    static func parserecentData(_ data: Data) -> RecentData? {
        do {
            
            let contactModelElement = try JSONDecoder().decode(RecentData.self, from: data)
            
            
            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
    
    static func parseGeoFenceData(_ data: Data) -> GeoFenceAddress? {
        do {
            
            let contactModelElement = try JSONDecoder().decode(GeoFenceAddress.self, from: data)
            
            
            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
    
    static func parseDrmKeyData(_ data: Data) -> DrmKey? {
        do {
            
            let contactModelElement = try JSONDecoder().decode(DrmKey.self, from: data)
            
            
            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
}
