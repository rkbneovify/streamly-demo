//
//  HomeNewVC+Extension.swift
//  Streamly
//
//  Created by Aditya Joshi on 14/12/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit
import Alamofire

// MARK: - UICollectionViewDelegate/ UICollectionViewDataSource
extension HomeNewVC : UICollectionViewDelegate, UICollectionViewDataSource, UICollectionViewDelegateFlowLayout {
    func numberOfSections(in collectionView: UICollectionView) -> Int
    {
        if self.collectionView == collectionView
        {
            guard let channels = filteredList, !channels.isEmpty else {
                emptyChanelLabel.isHidden = false
                return 0
            }
            emptyChanelLabel.isHidden = true
            return (channels.count + 1)
        }
        else {
            return 1
        }
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if self.collectionView == collectionView
        {
            guard let data =  filteredList else { return 0}
            return ((section == 0) ? daysData : ((data[section - 1].programs?.count ?? 0) + 1))
        }
        else {
            guard let data =  self.genrsList?.allGenre else { return fixedCategoriesArray.count}
            return data.count + fixedCategoriesArray.count
        }
    }


    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        //        if self.collectionView == collectionView
        //        {
        if indexPath.section == 0
        {
            let timeCell = cell as? TimeCollectionViewCell
            timeCell?.configureCell(indexInt: indexPath.item)
            timeCell?.layer.zPosition = 2000
            timeCell?.backgroundColor = .clear

        }
        else {

            if indexPath.row == 0
            {
                let channelCell = cell as? ChannelCollectionViewCell
                if  let  channel = filteredList?[indexPath.section - 1]
                {
                    channelCell?.configureWith(channel: channel)
                }
            }
            else {
                let programCell = cell as? ProgramCollectionViewCell
                
                if let channel = filteredList?[indexPath.section - 1], let programs = channel.programs
                {
                    if programs[indexPath.item - 1].title?.lowercased() == "no broadcast" {
                        print(programs[indexPath.item - 1].title ?? "")
                    }
                    self.headerTimeLbl.text = Utility.getDateFromTimeStampByFormat(from: filteredList?[indexPath.section - 1].programs?[indexPath.item - 1].start ?? 0.0, "E, d MMM yyyy")
                    programCell?.configureWithModel(program: programs[indexPath.item - 1], row : indexPath.item)
                } else {
                    
                }
            }
        }
        // }
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        if self.collectionView == collectionView
        {
            if indexPath.section == 0
            {
                guard let timeCell = collectionView.dequeueReusableCell(withReuseIdentifier: "timeCell", for: indexPath) as? TimeCollectionViewCell else {return UICollectionViewCell()}
                return timeCell
            }
            else {

                if indexPath.row == 0
                {
                    guard let channelCell = collectionView.dequeueReusableCell(withReuseIdentifier: "channelCell", for: indexPath) as? ChannelCollectionViewCell else {return UICollectionViewCell()}

                    return channelCell
                }
                else {
                    guard let programCell = collectionView.dequeueReusableCell(withReuseIdentifier: "programCell", for: indexPath) as? ProgramCollectionViewCell else {return UICollectionViewCell()}

                    return programCell
                }

            }
        }
        else {
            guard let cateGories = collectionView.dequeueReusableCell(withReuseIdentifier: AllGenrsListingCell.identifier, for: indexPath) as? AllGenrsListingCell else {return UICollectionViewCell()}
            switch indexPath.row {
            case CategoriesSelect.all.rawValue , CategoriesSelect.recent.rawValue,CategoriesSelect.myChannels.rawValue:
                cateGories.confiGureData(self.fixedCategoriesArray[indexPath.row], indexPath.row == self.selectedCategories ? true : false)

            default:
                if let data = genrsList?.allGenre
                {
                    cateGories.confiGureData(data[indexPath.row - 3], indexPath.row == self.selectedCategories ? true : false)

                }
            }

            return cateGories

        }
        
    }
    
    override func shouldUpdateFocus(in context: UIFocusUpdateContext) -> Bool {
        if self.collectionView == collectionView
        {
            if let chanelCell = context.nextFocusedView as? ChannelCollectionViewCell {
                guard let indexPath: IndexPath = self.collectionView.indexPath(for: chanelCell) as IndexPath? else {
                    self.discriptionView.isHidden = true
                    self.descriptionHeightConstraint.constant = 0
                    return true}
                // Call API for pagination
                
                if indexPath.section ==
                    (filteredList?.count ?? 0) && viewModel.isPageExist {
                    guard viewModel.currentPage <= viewModel.lastPage  else { return true }
                    lastIndexPath = indexPath
                    showLoading = true
//                    collectionView.reloadData()
                    showSpinner(onView: self.view)
                    if let lasIndexPath = lastIndexPath {
                        if viewModel.currentPage > 1 && (filteredList?.count ?? 0) > 0 {
                        collectionView.reloadItems(at: [lasIndexPath])
                        }
                    }
                    group.enter()
                    viewModel.getEPGData(viewModel.currentPage)
                } else {
                    showLoading = false
                    print("loading not called or pageExist is \(viewModel.isPageExist)")
                }
            }
            guard let programCell = context.nextFocusedView as? ProgramCollectionViewCell else {self.discriptionView.isHidden = true
                    self.descriptionHeightConstraint.constant = 0
                return true}
            guard let indexPath: IndexPath = self.collectionView.indexPath(for: programCell) as IndexPath? else { return true}
            // Call API for pagination when focus on programme also.
           /* if indexPath.section ==
                (filteredList?.count ?? 0) && viewModel.isPageExist {
                guard viewModel.currentPage <= viewModel.lastPage  else { return true }
                lastIndexPath = indexPath
                showLoading = true
//                    collectionView.reloadData()
                showSpinner(onView: self.view)
                if let lasIndexPath = lastIndexPath {
                    if viewModel.currentPage > 1 && (filteredList?.count ?? 0) > 0 {
                    collectionView.reloadSections(IndexSet(integer: lasIndexPath.section ))
                    }
                }
                group.enter()
                viewModel.getEPGData(viewModel.currentPage)
            } else {
                showLoading = false
                print("loading not called or pageExist is \(viewModel.isPageExist)")
            }*/
            
            if let channel = filteredList?[indexPath.section  - 1], let programs = channel.programs
            {
                guard (indexPath.item - 1) <= programs.count else { return true }
                self.headerTimeLbl.text = Utility.getDateFromTimeStampByFormat(from: programs[indexPath.item - 1].start ?? 0.0, "E, d MMM yyyy")
                self.discriptionView.isHidden = false
                self.descriptionHeightConstraint.constant = 300
                self.discriptionHeader.text = programs[indexPath.item - 1].title ?? ""
                self.discriptionThumbNail.sd_setImage(with: URL(string: channel.logo ?? ""), placeholderImage: UIImage(named: "Logo2"))
                self.descriptionLbl.text =  programs[indexPath.item - 1].description ?? ""
                self.discriptionStartTimeLbl.text = "Starts at \(Utility.getDateFromTimeStampByFormat(from: programs[indexPath.item - 1].start ?? 0.0, "hh:mm a") ?? "")"
            }
            //print(indexPath)
           
            return true
        } else {
            return true
        }
    }
    
    func indexPathForPreferredFocusedView(in collectionView: UICollectionView) -> IndexPath? {
        return self.lastIndexPath
    }

    private func moveFocus(to indexPath: IndexPath) {
        self.lastIndexPath = indexPath
        collectionView.setNeedsFocusUpdate()
        //collectionView.updateFocusIfNeeded() //can update it now if you need it to
    }

    func collectionView(_ collectionView: UICollectionView, shouldUpdateFocusIn context: UICollectionViewFocusUpdateContext) -> Bool {
        if self.collectionView == collectionView
        {
            self.collectionView.isScrollEnabled = false
            if context.nextFocusedIndexPath != nil && !collectionView.isScrollEnabled {
                if let nextFocusedIndexPath = context.nextFocusedIndexPath {
                    if nextFocusedIndexPath.section != 0 && nextFocusedIndexPath.row == 0
                    {
                        self.collectionView.isScrollEnabled = false
                        invalidateTimer()

                        collectionView.scrollToItem(
                            at: nextFocusedIndexPath,
                            at: .centeredVertically,
                            animated: true)
                        return true

                    }
                    else if nextFocusedIndexPath.section == 0
                    {
                        self.collectionView.isScrollEnabled = false
                        return true
                    }
                    else {
                        self.collectionView.isScrollEnabled = true
                        return true
                        //                    invalidateTimer()
                        //                    collectionView.scrollToItem(
                        //                        at: nextFocusedIndexPath,
                        //                        at: .centeredHorizontally,
                        //                        animated: false)
                    }
                }
            }
            return true
        }
        else {
            if context.nextFocusedIndexPath != nil && !collectionView.isScrollEnabled {
                if let nextFocusedIndexPath = context.nextFocusedIndexPath {
                    collectionView.scrollToItem(
                        at: nextFocusedIndexPath,
                        at: .centeredHorizontally,
                        animated: true)

                }

            }
            return true
        }

    }
    
    func collectionView(_ collectionView: UICollectionView, didUpdateFocusIn context: UICollectionViewFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if let indexPath = context.nextFocusedIndexPath,
           let cell = collectionView.cellForItem(at: indexPath) {
            if viewModel.currentPage > 1 && (filteredList?.count ?? 0) > 0 {
            self.collectionView.scrollToItem(at: indexPath, at:
             .centeredVertically, animated: true)
                self.scrollCell = cell
                NSObject.cancelPreviousPerformRequests(withTarget: self, selector: #selector(callScrollMethod), object: nil)
                perform(#selector(callScrollMethod), with: nil, afterDelay: 1)
            }
        }
    }
    

    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        if self.collectionView == collectionView
        {
            if indexPath.section != 0
            {
                let flag = self.geoData?.success ?? false
                if self.geoData?.response_code == 402 {
                    showAlert(withMessage: self.geoData?.message ?? "",setFirstButtonTitle: "Logout") { _ in
                        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                        Utility.logOut()
                    }
                    return
                }
                guard let item = filteredList?[(indexPath.section - 1)] else {return}
//                if item.programs?[indexPath.item - 1].title?.lowercased() == "no broadcast"{
//                    print("it's not playable.")
//                    return
//                }
                present(videoPlaybackViewController, animated: true) {
                    self.videoPlaybackViewController.channelObject = item
                    self.videoPlaybackViewController.isAllowedZipCode = flag
                    self.videoPlaybackViewController.delegatee = self
                    self.videoPlaybackViewController.str = self.geoData?.popupmsg ?? ""
                    self.videoPlaybackViewController.isPopAllow = self.geoData?.popupmsgtoggle ?? true
                    self.videoPlaybackViewController.isfromRcent = self.selectedCategories == 1 ? true : false
                    self.videoPlaybackViewController.idS = "\(item.id ?? 0)"
                    self.videoPlaybackViewController.filterData = self.filteredList
                    self.videoPlaybackViewController.loadAndPlay()
                }
                //                let viewController = PlayerVC.instantiateFromAppStoryboard(appStoryboard: .Main)
//                viewController.channelObject = item
//                viewController.isAllowedZipCode = flag
//                viewController.str = geoData?.popupmsg ?? ""
//                viewController.isPopAllow = self.geoData?.popupmsgtoggle ?? true
//                self.present(viewController, animated: true, completion: nil)
            }
        }
        else {
            viewModel.currentPage = 1
            channelList = nil
            switch indexPath.row {
            case CategoriesSelect.all.rawValue , CategoriesSelect.recent.rawValue,CategoriesSelect.myChannels.rawValue:
                self.selectCategories(indexPath.row, key: nil)
            default:
                self.selectCategories(indexPath.row, key: self.genrsList?.allGenre?[indexPath.row - 3])

            }

        }
    }
}

// MARK:  Timer
extension HomeNewVC {
    func startTimer()
    {
        timer = Timer.scheduledTimer(withTimeInterval: 5, repeats: true) { timer in
            self.getCurrentTime()
        }
    }
    
    func invalidateTimer()
    {
        if let _ = timer
        {
            timer!.invalidate()
            timer = nil
            setLiveBtnView(UIColor.lightGray,tittle: "GO LIVE")

        }
    }

    func setLiveBtnView(_ color:UIColor , tittle:String)
    {
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .normal)
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .focused)
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .highlighted)
        liveButton.setTitle(tittle, for: .normal)
    }
    
    func getCurrentTime()
    {
        let currentTime = formatter.string(from: Date())
        if currentTimeLabel.text != currentTime
        {
            currentTimeLabel.text = currentTime
            var offset = self.collectionView.contentOffset
            offset.x += 13.5
            self.collectionView.reloadData()
            self.collectionView.layoutIfNeeded()

            DispatchQueue.main.async(execute: {
                /* Scroll to the previous saved position */
                self.collectionView.setContentOffset(offset, animated: false)
            })

        }

    }
}

// MARK: - Logic for make programList data.
extension HomeNewVC {
    func getUpdatedChannelList(_ channel: [ChannelDetails]) -> [ChannelDetails] {
        var tmpChannel = [ChannelDetails]()
//        return channel
        channel.forEach { chDetail in
            let yesterDayDate = Date().dayBefore.string(format: "dd/MM/yyyy")
            let endTime = "\(Date().dayAfter.string(format: "dd/MM/yyyy")) 12:00 PM"
            var startTime = "\(yesterDayDate) 12:00 AM"
            var newPrograme = [ProgramArray]()
            print("Channel:\(chDetail.StationName ?? "")")
            chDetail.programs?.forEach({ proDetail in
                print("programme Title: \(proDetail.title ?? "") \nStart: \(Utility.getDateFromTimeStampByFormat(from: proDetail.start ?? 0) ?? "") - end: \(Utility.getDateFromTimeStampByFormat(from: proDetail.end ?? 0) ?? "")")
                // chekck if start time is existing on programe or not
                if chDetail.programs?.last?.end == proDetail.end && endTime > Utility.getDateFromTimeStampByFormat(from: proDetail.end ?? 0, "dd/MM/yyyy hh:mm a") ?? "" && (chDetail.programs?.count ?? 0) == 1 {
                let lastmakeNoBroadcast = ProgramArray(startTZ: proDetail.end ?? 0.0, endTZ:  makeNewTime(startTime: endTime), start: proDetail.end ?? 0.0, end: makeNewTime(startTime: endTime), title: "No broadcast", timezoneName: "", description: "")
                    let firstmakeNoBroadcast = ProgramArray(startTZ: makeNewTime(startTime: startTime), endTZ:  proDetail.starttz ?? 0.0, start: makeNewTime(startTime: startTime), end: proDetail.start ?? 0.0, title: "No broadcast", timezoneName: "", description: "")
//                    if Utility.getDateFromTimeStampByFormat(from: proDetail.start ?? 0) != startTime {
                newPrograme.append(firstmakeNoBroadcast)
                newPrograme.append(proDetail)
                newPrograme.append(lastmakeNoBroadcast)
                startTime = Utility.getDateFromTimeStampByFormat(from: lastmakeNoBroadcast.end ?? 0, "dd/MM/yyyy hh:mm a") ?? ""
                } else if chDetail.programs?.last?.end == proDetail.end && endTime > Utility.getDateFromTimeStampByFormat(from: proDetail.end ?? 0, "dd/MM/yyyy hh:mm a") ?? "" {
                let makeNoBroadcast = ProgramArray(startTZ: proDetail.end ?? 0.0, endTZ:  makeNewTime(startTime: endTime), start: proDetail.end ?? 0.0, end: makeNewTime(startTime: endTime), title: "No broadcast", timezoneName: "", description: "")
//                    if Utility.getDateFromTimeStampByFormat(from: proDetail.start ?? 0) != startTime {
                newPrograme.append(proDetail)
                newPrograme.append(makeNoBroadcast)
                startTime = Utility.getDateFromTimeStampByFormat(from: makeNoBroadcast.end ?? 0, "dd/MM/yyyy hh:mm a") ?? ""
                } else if startTime == Utility.getDateFromTimeStampByFormat(from: proDetail.start ?? 0, "dd/MM/yyyy hh:mm a") ?? "" { // yes
                    newPrograme.append(proDetail)
                    startTime = Utility.getDateFromTimeStampByFormat(from: proDetail.end ?? 0, "dd/MM/yyyy hh:mm a") ?? ""
                }  else {// Add no broadcast cell with end time
                    let makeNoBroadcast = ProgramArray(startTZ: makeNewTime(startTime: startTime), endTZ: proDetail.start ?? 0.0, start: makeNewTime(startTime: startTime), end: proDetail.start ?? 0.0, title: "No broadcast", timezoneName: "", description: "")
//                    if Utility.getDateFromTimeStampByFormat(from: proDetail.start ?? 0) != startTime {
                        newPrograme.append(makeNoBroadcast)
//                    }
                    print("makeNoBroadcast start:\(Utility.getDateFromTimeStampByFormat(from: makeNoBroadcast.start ?? 0) ?? "")- \(Utility.getDateFromTimeStampByFormat(from: makeNoBroadcast.end ?? 0) ?? "")")
                    newPrograme.append(proDetail)
                    // added latest start time
                    startTime = Utility.getDateFromTimeStampByFormat(from: proDetail.end ?? 0, "dd/MM/yyyy hh:mm a") ?? ""
                }
            })
            let updatedChannel = ChannelDetails(id: chDetail.id ?? 0, stationName: chDetail.StationName ?? "", url: chDetail.url ?? "", logo: chDetail.logo ?? "", callSign: chDetail.CallSign ?? "", programs: newPrograme)
            tmpChannel.append(updatedChannel)
            newPrograme.removeAll()
        }
        return tmpChannel
    }
    
    func cellScrollToVisibleTItle(_ cell: ProgramCollectionViewCell) {
            if self.collectionView.contentOffset.x > cell.frame.origin.x - 300 {
                self.collectionView.contentOffset.x = cell.frame.origin.x - 300
            }
    }
    
    @objc func callScrollMethod() {
        if self.scrollCell is ProgramCollectionViewCell {
            cellScrollToVisibleTItle(scrollCell as! ProgramCollectionViewCell)
        }
    }
    
    
    
    func makeNewTime(startTime: String) -> TimeInterval  {
        let formatter = DateFormatter()
        formatter.dateFormat = "dd/MM/yyyy hh:mm a"
        formatter.locale = Calendar.current.locale//Locale(identifier: "en_US_POSIX")
        formatter.timeZone = Calendar.current.timeZone
        return  formatter.date(from: startTime)?.timeIntervalSince1970 ?? 0
    }
}
